<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';

use RemnWeb\Auth;

header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if (Auth::sendCode($email)) {
        echo json_encode(['status' => 'ok']);
        exit;
    }
    http_response_code(500);
    echo json_encode(['error' => 'send_failed']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_email']);
}
