<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';

use RemnWeb\Auth;

header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$code = $input['code'] ?? '';

if ($email && $code && Auth::verifyCode($email, $code)) {
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_code']);
}
