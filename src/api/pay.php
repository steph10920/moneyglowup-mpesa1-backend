<?php
require_once __DIR__ . '/../config/mpesa.php';

// Set response headers
header('Content-Type: application/json');

try {
    // Get request data
    $request = json_decode(file_get_contents('php://input'), true);

    $phone = $request['phone'];
    $amount = $request['amount'];
    $item = $request['item'];

    if (!$phone || !$amount || !$item) {
        throw new Exception("Missing required fields.");
    }

    // Call the M-Pesa STK Push function
    $response = initiateMpesaStkPush($phone, $amount, $item);

    // Return success response
    echo json_encode(['success' => true, 'message' => 'Payment initiated.', 'response' => $response]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
