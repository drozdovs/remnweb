<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';

use RemnWeb\Admin;
use RemnWeb\DB;

header('Content-Type: application/json');
if (!Admin::user()) {
    http_response_code(401);
    echo json_encode(['error' => 'unauthenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = (int)($data['id'] ?? 0);
$block = (int)($data['block'] ?? 0);
$db = DB::get();
$stmt = $db->prepare('UPDATE users SET blocked=:b WHERE id=:i');
$stmt->execute([':b' => $block, ':i' => $id]);

echo json_encode(['status' => 'ok']);
