<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';

use RemnWeb\Admin;

header('Content-Type: application/json');
$admin = Admin::user();
if ($admin) {
    echo json_encode(['username' => $admin['username']]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'unauthenticated']);
}
