<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require './mysqliOOP.php';
// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
    // Access standard form fields via $_POST
    $email = $_POST['email'] ?? null;
    $password  = $_POST['password'] ?? null;

    $config = require __DIR__ . '/config.php';

    $db = new DataBaseConnection($config["host"],$config["user"],$config["pass"],$config["db"]);

    $vysledky = $db->dotazatSePrepared("SELECT password, id FROM Users WHERE email=? LIMIT 1","s",[$email]);
    
    if (hash_equals($vysledky[0]["password"], $password)) {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);    

        if($db->vlozitPrepared("INSERT INTO `SignInTokeny` (`idTokenu`, `casVytvoreni`, `token`, `idUsera`) VALUES (NULL, current_timestamp(), ?, ?)","ss",[$tokenHash, $vysledky[0]["id"]])["affected_rows"] > 0){
            echo json_encode([
                'status' => 'success',
                'token' => $token,
            ]);
            exit;
        }else {
            echo json_encode(
                ['status' => 'error',
                'token' => 'unexpected error']
            );
        }
        
        
    }

    echo json_encode(['status' => 'error', 'token' => 'Invalid message']);
    exit;

    
    
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
        ]);
    }
}

// Fallback for non-POST requests
http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
?>