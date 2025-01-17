<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Define the base directory
$baseDir = dirname(__DIR__);

// Get the requested URI
$requestUri = $_SERVER['REQUEST_URI'];

// Handle API routing
if (strpos($requestUri, '/api/') === 0) {
    $endpoint = $baseDir . '/src' . $requestUri . '.php';
    if (file_exists($endpoint)) {
        require_once $endpoint;
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Endpoint not found']);
        exit;
    }
}

// Default response for invalid routes
http_response_code(404);
echo 'Page not found';
