<?php
header("Access-Control-Allow-Origin: https://moneyglowup-by-brenda.vercel.app");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Handle OPTIONS request for preflight checks
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require_once 'mpesa.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $phone = $data['phone'] ?? null;
    $amount = $data['amount'] ?? null;
    $accountReference = $data['accountReference'] ?? 'Account';

    if (!$phone || !$amount) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Phone and amount are required']);
        exit;
    }

    $mpesa = new Mpesa();
    $response = $mpesa->lipaNaMpesa($phone, $amount, $accountReference);

    if (isset($response['ResponseCode']) && $response['ResponseCode'] == '0') {
        echo json_encode(['success' => true, 'message' => 'STK Push sent successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to send STK Push', 'response' => $response]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
