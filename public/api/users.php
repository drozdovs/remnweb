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
$users = $db->query('SELECT id, email, blocked, trial_used FROM users')->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($users);
