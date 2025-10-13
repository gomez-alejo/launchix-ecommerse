#!/usr/bin/env php
<?php

echo "🔐 PROBANDO API DE AUTENTICACIÓN\n";
echo "================================\n\n";

// Función para hacer requests
function makeRequest($url, $method = 'GET', $data = null, $token = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

$baseUrl = 'http://localhost/launchix-ecommerse/public/api/v1';

// 1. Probar registro
echo "1️⃣  PROBANDO REGISTRO DE USUARIO\n";
echo "────────────────────────────────\n";

$userData = [
    'name' => 'Usuario de Prueba API',
    'username' => 'usuarioapi_' . time(),
    'email' => 'prueba_' . time() . '@launchix.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'phone' => '+57 300 123 4567',
    'city' => 'Bogotá',
    'department' => 'Cundinamarca'
];

$result = makeRequest($baseUrl . '/register', 'POST', $userData);

echo "Código HTTP: " . $result['code'] . "\n";

if ($result['error']) {
    echo "❌ Error de conexión: " . $result['error'] . "\n\n";
    exit(1);
}

if ($result['code'] === 201) {
    echo "✅ ¡Registro exitoso!\n";
    $registerResponse = json_decode($result['response'], true);

    if (isset($registerResponse['access_token'])) {
        $token = $registerResponse['access_token'];
        echo "🎟️  Token obtenido: " . substr($token, 0, 20) . "...\n\n";

        // 2. Probar endpoint /me
        echo "2️⃣  PROBANDO PERFIL DEL USUARIO (/me)\n";
        echo "───────────────────────────────────────\n";

        $meResult = makeRequest($baseUrl . '/me', 'GET', null, $token);
        echo "Código HTTP: " . $meResult['code'] . "\n";

        if ($meResult['code'] === 200) {
            echo "✅ ¡Perfil obtenido correctamente!\n";
            $meData = json_decode($meResult['response'], true);
            echo "👤 Usuario: " . $meData['data']['name'] . "\n";
            echo "📧 Email: " . $meData['data']['email'] . "\n\n";
        } else {
            echo "❌ Error al obtener perfil: " . $meResult['response'] . "\n\n";
        }

        // 3. Probar logout
        echo "3️⃣  PROBANDO CERRAR SESIÓN\n";
        echo "──────────────────────────\n";

        $logoutResult = makeRequest($baseUrl . '/logout', 'POST', [], $token);
        echo "Código HTTP: " . $logoutResult['code'] . "\n";

        if ($logoutResult['code'] === 200) {
            echo "✅ ¡Logout exitoso!\n";
            echo "🔒 Token revocado correctamente\n\n";
        } else {
            echo "❌ Error en logout: " . $logoutResult['response'] . "\n\n";
        }

    } else {
        echo "❌ No se recibió token en la respuesta\n";
        echo "Respuesta: " . $result['response'] . "\n\n";
    }

} else {
    echo "❌ Error en el registro\n";
    echo "Respuesta: " . $result['response'] . "\n\n";
}

echo "🏁 PRUEBAS COMPLETADAS\n";
echo "======================\n";
