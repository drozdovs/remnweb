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

$db = DB::get();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $plans = $db->query('SELECT * FROM plans')->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($plans);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare('UPDATE plans SET price=:p, trial=:t WHERE name=:n');
    $stmt->execute([
        ':p' => $data['price'],
        ':t' => $data['trial'] ? 1 : 0,
        ':n' => $data['name']
    ]);
    echo json_encode(['status' => 'ok']);
    exit;
}
