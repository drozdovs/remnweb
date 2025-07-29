<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';

use RemnWeb\Auth;

header('Content-Type: application/json');
$user = Auth::user();
if ($user) {
    echo json_encode(['email' => $user['email']]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'unauthenticated']);
}
