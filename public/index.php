<?php

header('Content-Type: application/json');

$requestUri = explode('?', $_SERVER['REQUEST_URI'])[0];

if ($requestUri === '/api/pay') {
    require_once '../src/api/lipa.php';
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Endpoint not found']);
}
?>
