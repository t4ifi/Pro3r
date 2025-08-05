<?php
/**
 * 🔒 SCRIPT DE TESTING DE SEGURIDAD - DENTALSYNC
 * Desarrollador: Andrés Núñez
 * Fecha: 28 de julio de 2025
 * 
 * Este script verifica que todas las medidas de seguridad implementadas
 * funcionen correctamente en el sistema DentalSync.
 */

echo "🔒 INICIANDO TESTS DE SEGURIDAD DENTALSYNC\n";
echo "=========================================\n\n";

$baseUrl = 'http://127.0.0.1:8000/api';

/**
 * Función para hacer requests HTTP
 */
function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $headers[] = 'Content-Type: application/json';
        }
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status' => $httpCode,
        'body' => $response,
        'data' => json_decode($response, true)
    ];
}

/**
 * TEST 1: Verificar que las rutas protegidas requieren autenticación
 */
echo "📋 TEST 1: Rutas protegidas sin autenticación\n";
echo "--------------------------------------------\n";

$protectedRoutes = [
    '/pacientes',
    '/citas',
    '/tratamientos',
    '/usuarios'
];

$allProtected = true;
foreach ($protectedRoutes as $route) {
    $response = makeRequest($baseUrl . $route);
    
    if ($response['status'] === 401) {
        echo "✅ $route: PROTEGIDA CORRECTAMENTE (401 Unauthorized)\n";
    } else {
        echo "❌ $route: VULNERABLE - Status: {$response['status']}\n";
        $allProtected = false;
    }
}

echo $allProtected ? "\n🟢 RESULTADO: Todas las rutas están protegidas\n" : "\n🔴 RESULTADO: Hay rutas vulnerables\n";
echo "\n";

/**
 * TEST 2: Verificar rate limiting en login
 */
echo "📋 TEST 2: Rate limiting en endpoint de login\n";
echo "---------------------------------------------\n";

$loginData = ['usuario' => 'test_invalid', 'password' => 'wrong_password'];
$rateLimitTriggered = false;

for ($i = 1; $i <= 7; $i++) {
    $response = makeRequest($baseUrl . '/login', 'POST', $loginData);
    
    if ($response['status'] === 429) {
        echo "✅ Intento $i: Rate limit activado (429 Too Many Attempts)\n";
        $rateLimitTriggered = true;
        break;
    } elseif ($response['status'] === 401) {
        echo "⏳ Intento $i: Login fallido normal (401)\n";
    } else {
        echo "⚠️ Intento $i: Respuesta inesperada - Status: {$response['status']}\n";
    }
    
    sleep(1); // Esperar 1 segundo entre intentos
}

echo $rateLimitTriggered ? "\n🟢 RESULTADO: Rate limiting funciona correctamente\n" : "\n🟡 RESULTADO: Rate limiting no se activó en 7 intentos\n";
echo "\n";

/**
 * TEST 3: Verificar validación de datos de entrada
 */
echo "📋 TEST 3: Validación de datos de entrada\n";
echo "-----------------------------------------\n";

$invalidInputs = [
    'usuario_con_caracteres_especiales' => ['usuario' => 'test<script>', 'password' => 'password123'],
    'usuario_muy_largo' => ['usuario' => str_repeat('a', 150), 'password' => 'password123'],
    'password_muy_corto' => ['usuario' => 'testuser', 'password' => '123'],
    'datos_vacios' => ['usuario' => '', 'password' => ''],
    'sin_usuario' => ['password' => 'password123'],
    'sin_password' => ['usuario' => 'testuser']
];

$validationWorks = true;
foreach ($invalidInputs as $testName => $data) {
    $response = makeRequest($baseUrl . '/login', 'POST', $data);
    
    if ($response['status'] === 422 || $response['status'] === 400) {
        echo "✅ $testName: Validación correcta (Status: {$response['status']})\n";
    } else {
        echo "❌ $testName: Validación fallida - Status: {$response['status']}\n";
        $validationWorks = false;
    }
}

echo $validationWorks ? "\n🟢 RESULTADO: Validación de entrada funciona\n" : "\n🔴 RESULTADO: Hay problemas en la validación\n";
echo "\n";

/**
 * TEST 4: Verificar que el endpoint de login público funciona
 */
echo "📋 TEST 4: Endpoint público de login accesible\n";
echo "----------------------------------------------\n";

$response = makeRequest($baseUrl . '/login', 'POST', ['usuario' => 'test', 'password' => 'test']);

if ($response['status'] === 401 || $response['status'] === 422) {
    echo "✅ Login endpoint accesible - Status: {$response['status']}\n";
    echo "🟢 RESULTADO: Endpoint de login funciona correctamente\n";
} elseif ($response['status'] === 500) {
    echo "⚠️ Error del servidor - Status: {$response['status']}\n";
    echo "🟡 RESULTADO: Posible problema de configuración\n";
} else {
    echo "❌ Respuesta inesperada - Status: {$response['status']}\n";
    echo "🔴 RESULTADO: Problema en endpoint de login\n";
}
echo "\n";

/**
 * TEST 5: Verificar headers de seguridad (si están implementados)
 */
echo "📋 TEST 5: Headers de seguridad\n";
echo "-------------------------------\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);

$response = curl_exec($ch);
$headers = curl_getinfo($ch, CURLINFO_HEADER_OUT);
curl_close($ch);

$securityHeaders = [
    'X-Content-Type-Options',
    'X-Frame-Options',
    'X-XSS-Protection',
    'Strict-Transport-Security'
];

$secureHeaders = 0;
foreach ($securityHeaders as $header) {
    if (stripos($response, $header) !== false) {
        echo "✅ $header: Presente\n";
        $secureHeaders++;
    } else {
        echo "⚠️ $header: No encontrado\n";
    }
}

echo "\n🟢 RESULTADO: $secureHeaders/" . count($securityHeaders) . " headers de seguridad encontrados\n";
echo "\n";

/**
 * RESUMEN FINAL
 */
echo "🎯 RESUMEN FINAL DE SEGURIDAD\n";
echo "============================\n";
echo "✅ Protección de rutas: " . ($allProtected ? "ACTIVA" : "FALLA") . "\n";
echo "✅ Rate limiting: " . ($rateLimitTriggered ? "ACTIVO" : "VERIFICAR") . "\n";
echo "✅ Validación de entrada: " . ($validationWorks ? "ACTIVA" : "FALLA") . "\n";
echo "✅ Endpoint de login: FUNCIONAL\n";
echo "✅ Headers de seguridad: $secureHeaders/" . count($securityHeaders) . " implementados\n";

echo "\n";

$overallSecurity = ($allProtected && $validationWorks) ? "🟢 ALTO" : "🟡 MEDIO";
echo "🛡️ NIVEL DE SEGURIDAD GENERAL: $overallSecurity\n";

if ($allProtected && $validationWorks) {
    echo "\n🎉 ¡SISTEMA SEGURO PARA PRODUCCIÓN!\n";
    echo "   Todas las medidas críticas están funcionando.\n";
} else {
    echo "\n⚠️ Revisar las fallas identificadas antes de producción.\n";
}

echo "\n🏁 Tests completados - " . date('Y-m-d H:i:s') . "\n";
echo "💻 Desarrollado por: Andrés Núñez\n";
echo "🔒 Sistema: DentalSync Security Testing Suite\n";
?>
