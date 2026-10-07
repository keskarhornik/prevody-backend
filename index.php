<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Access standard form fields via $_POST
    $message = $_POST['message'] ?? null;
    $action  = $_POST['action'] ?? null;

    if ($message === 'Hi') {
        echo json_encode(['status' => 'success', 'message' => 'Hello']);
        exit;
    }

    echo json_encode(['status' => 'error', 'message' => 'Invalid message']);
    exit;
}

// Fallback for non-POST requests
http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
?>