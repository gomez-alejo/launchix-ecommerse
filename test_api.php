<?php

// Test API Registration Endpoint
echo "Testing API Registration...\n\n";

$url = 'http://127.0.0.1:8001/api/v1/register';
$data = [
    'name' => 'Test User API',
    'username' => 'testapi_' . time(),
    'email' => 'testapi_' . time() . '@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'phone' => '1234567890',
    'city' => 'Bogotá',
    'department' => 'Cundinamarca'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n\n";

if ($httpCode === 201) {
    $responseData = json_decode($response, true);
    $token = $responseData['access_token'] ?? null;

    if ($token) {
        echo "Registration successful! Testing /me endpoint...\n\n";

        // Test /me endpoint
        $meUrl = 'http://127.0.0.1:8001/api/v1/me';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $meUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);

        $meResponse = curl_exec($ch);
        $meHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        echo "/me HTTP Code: " . $meHttpCode . "\n";
        echo "/me Response: " . $meResponse . "\n\n";

        // Test logout
        echo "Testing logout...\n\n";
        $logoutUrl = 'http://127.0.0.1:8001/api/v1/logout';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $logoutUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);

        $logoutResponse = curl_exec($ch);
        $logoutHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        echo "Logout HTTP Code: " . $logoutHttpCode . "\n";
        echo "Logout Response: " . $logoutResponse . "\n";
    }
}
