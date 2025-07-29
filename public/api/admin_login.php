<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';

use RemnWeb\Admin;

header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$user = $input['user'] ?? '';
$pass = $input['pass'] ?? '';
if ($user && $pass && Admin::login($user, $pass)) {
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'invalid']);
}
