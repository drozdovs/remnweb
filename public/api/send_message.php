<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';

use RemnWeb\Admin;
use RemnWeb\DB;
use RemnWeb\Mailer;

header('Content-Type: application/json');
if (!Admin::user()) {
    http_response_code(401);
    echo json_encode(['error' => 'unauthenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = (int)($data['id'] ?? 0);
$subject = $data['subject'] ?? '';
$body = $data['body'] ?? '';

$db = DB::get();
$stmt = $db->prepare('SELECT email FROM users WHERE id = :i');
$stmt->execute([':i' => $id]);
$email = $stmt->fetchColumn();
if ($email) {
    $mailer = new Mailer();
    $mailer->send($email, $subject, $body);
}

echo json_encode(['status' => 'ok']);
