<?php
/**
 * 🔧 SCRIPT DE TESTING DIRECTO DE MIDDLEWARE
 * Para verificar que el middleware AuthenticateApi funciona correctamente
 */

echo "🔧 TESTING DIRECTO DE MIDDLEWARE AUTHAPI\n";
echo "=======================================\n\n";

$baseUrl = 'http://127.0.0.1:8000/api';

// Test simple con cURL
function testRoute($url, $withAuth = false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    if ($withAuth) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer test_token_for_development_123456789abcdef'
        ]);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status' => $httpCode,
        'body' => $response
    ];
}

echo "📋 Test 1: Ruta /me sin autenticación\n";
$result = testRoute($baseUrl . '/me');
echo "Status: {$result['status']}\n";
echo "Response: " . substr($result['body'], 0, 200) . "...\n\n";

echo "📋 Test 2: Ruta /me con token válido\n";
$result = testRoute($baseUrl . '/me', true);
echo "Status: {$result['status']}\n";
echo "Response: " . substr($result['body'], 0, 200) . "...\n\n";

echo "📋 Test 3: Ruta /pacientes sin autenticación\n";
$result = testRoute($baseUrl . '/pacientes');
echo "Status: {$result['status']}\n";
echo "Response: " . substr($result['body'], 0, 200) . "...\n\n";

echo "📋 Test 4: Ruta /pacientes con token válido\n";
$result = testRoute($baseUrl . '/pacientes', true);
echo "Status: {$result['status']}\n";
echo "Response: " . substr($result['body'], 0, 200) . "...\n\n";

echo "🏁 Tests completados\n";
?>
