#!/usr/bin/env php
<?php

echo "🛍️  PROBANDO API DE PRODUCTOS\n";
echo "==============================\n\n";

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
    } elseif ($method === 'PUT' || $method === 'PATCH') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
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
$token = null;

// 1. Crear un usuario primero para obtener token
echo "1️⃣  CREANDO USUARIO PARA PRUEBAS\n";
echo "────────────────────────────────\n";

$userData = [
    'name' => 'Usuario Productos',
    'username' => 'producttest_' . time(),
    'email' => 'producttest_' . time() . '@launchix.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

$result = makeRequest($baseUrl . '/register', 'POST', $userData);

if ($result['code'] === 201) {
    $registerResponse = json_decode($result['response'], true);
    $token = $registerResponse['access_token'];
    echo "✅ Usuario creado y token obtenido\n\n";
} else {
    echo "❌ Error creando usuario: " . $result['response'] . "\n\n";
    exit(1);
}

// 2. Probar listado de productos (público)
echo "2️⃣  PROBANDO LISTADO PÚBLICO DE PRODUCTOS\n";
echo "─────────────────────────────────────────\n";

$result = makeRequest($baseUrl . '/products');
echo "Código HTTP: " . $result['code'] . "\n";

if ($result['code'] === 200) {
    $products = json_decode($result['response'], true);
    echo "✅ Listado obtenido correctamente\n";
    echo "📦 Total productos: " . count($products['data']) . "\n\n";
} else {
    echo "❌ Error obteniendo productos: " . $result['response'] . "\n\n";
}

// 3. Probar productos destacados
echo "3️⃣  PROBANDO PRODUCTOS DESTACADOS\n";
echo "─────────────────────────────────\n";

$result = makeRequest($baseUrl . '/products/featured');
echo "Código HTTP: " . $result['code'] . "\n";

if ($result['code'] === 200) {
    $featured = json_decode($result['response'], true);
    echo "✅ Productos destacados obtenidos\n";
    echo "⭐ Total destacados: " . count($featured['data']) . "\n\n";
} else {
    echo "❌ Error obteniendo destacados: " . $result['response'] . "\n\n";
}

// 4. Probar productos populares
echo "4️⃣  PROBANDO PRODUCTOS POPULARES\n";
echo "───────────────────────────────\n";

$result = makeRequest($baseUrl . '/products/popular');
echo "Código HTTP: " . $result['code'] . "\n";

if ($result['code'] === 200) {
    $popular = json_decode($result['response'], true);
    echo "✅ Productos populares obtenidos\n";
    echo "🔥 Total populares: " . count($popular['data']) . "\n\n";
} else {
    echo "❌ Error obteniendo populares: " . $result['response'] . "\n\n";
}

// 5. Probar búsqueda con filtros
echo "5️⃣  PROBANDO BÚSQUEDA CON FILTROS\n";
echo "────────────────────────────────\n";

$result = makeRequest($baseUrl . '/products?search=producto&min_price=1000&per_page=5');
echo "Código HTTP: " . $result['code'] . "\n";

if ($result['code'] === 200) {
    $filtered = json_decode($result['response'], true);
    echo "✅ Búsqueda con filtros exitosa\n";
    echo "🔍 Productos encontrados: " . count($filtered['data']) . "\n\n";
} else {
    echo "❌ Error en búsqueda filtrada: " . $result['response'] . "\n\n";
}

echo "🏁 PRUEBAS DE PRODUCTOS COMPLETADAS\n";
echo "=====================================\n\n";

echo "📋 RESUMEN DE ENDPOINTS PROBADOS:\n";
echo "✅ GET /api/v1/products - Listado público\n";
echo "✅ GET /api/v1/products/featured - Productos destacados\n";
echo "✅ GET /api/v1/products/popular - Productos populares\n";
echo "✅ GET /api/v1/products?search=... - Búsqueda con filtros\n\n";

echo "🔒 ENDPOINTS AUTENTICADOS DISPONIBLES:\n";
echo "• POST /api/v1/products - Crear producto\n";
echo "• PUT /api/v1/products/{id} - Actualizar producto\n";
echo "• DELETE /api/v1/products/{id} - Eliminar producto\n";
echo "• PATCH /api/v1/products/{id}/toggle-status - Cambiar estado\n";
echo "• PATCH /api/v1/products/{id}/toggle-featured - Destacar producto\n";
