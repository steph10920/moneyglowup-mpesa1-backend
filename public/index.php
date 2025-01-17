<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: https://moneyglowup-by-brenda.vercel.app");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$requestUri = explode('?', $_SERVER['REQUEST_URI'])[0];

if ($requestUri === '/api/pay') {
    require_once '../src/api/lipa.php';
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Endpoint not found']);
}
?>
