<?php
/**
 * Test específico para entender qué está pasando
 */

function testSpecificRoute($url, $description) {
    echo "Testing: $description\n";
    echo "URL: $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "Status: $httpCode\n";
    if ($error) {
        echo "cURL Error: $error\n";
    }
    echo "Response Length: " . strlen($response) . "\n";
    echo "Response: " . substr($response, 0, 200) . "...\n";
    echo "---\n\n";
}

// Test rutas
testSpecificRoute('http://127.0.0.1:8000/api/test-pacientes', 'Pacientes SIN middleware');
testSpecificRoute('http://127.0.0.1:8000/api/login', 'Login endpoint');
testSpecificRoute('http://127.0.0.1:8000/api/me', 'Me endpoint CON middleware');
testSpecificRoute('http://127.0.0.1:8000/api/pacientes', 'Pacientes CON middleware');
?>
