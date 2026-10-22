<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
require "./mysqliOOP.php" ;

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Access standard form fields via $_POST
    $message = $_POST['message'] ?? null;
    $action  = $_POST['action'] ?? null;

    $config = require __DIR__ . '/config.php';

    $db = new DataBaseConnection($config["host"],$config["user"],$config["pass"],$config["db"]);
    $searchToken = $db->dotazatSePrepared("SELECT idTokenu, token, idUsera FROM SignInTokeny WHERE token=?", "s", [$message]);
    if ($searchToken[0]["token"] === $message) {
        

        echo json_encode(['status' => 'success', 'message' => $searchToken[0]["idUsera"]]);
        exit;
    }

    echo json_encode(['status' => 'error', 'message' => 'Invalid message']);
    exit;
}

// Fallback for non-POST requests
http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
?>