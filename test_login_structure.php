<?php
/**
 * Test específico del login para verificar la estructura de respuesta
 */

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'usuario' => 'admin',
    'password' => 'admin123'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Si es exitoso, verificar estructura
if ($httpCode === 200) {
    $data = json_decode($response, true);
    
    echo "Estructura de respuesta:\n";
    echo "- success: " . ($data['success'] ?? 'undefined') . "\n";
    echo "- message: " . ($data['message'] ?? 'undefined') . "\n";
    echo "- data: " . (isset($data['data']) ? 'presente' : 'ausente') . "\n";
    
    if (isset($data['data'])) {
        echo "  - data.id: " . ($data['data']['id'] ?? 'undefined') . "\n";
        echo "  - data.usuario: " . ($data['data']['usuario'] ?? 'undefined') . "\n";
        echo "  - data.nombre: " . ($data['data']['nombre'] ?? 'undefined') . "\n";
        echo "  - data.rol: " . ($data['data']['rol'] ?? 'undefined') . "\n";
        echo "  - data.token: " . (isset($data['data']['token']) ? 'presente' : 'ausente') . "\n";
    }
}
?>
