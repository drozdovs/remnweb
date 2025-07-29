<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use RemnWeb\Auth;
use RemnWeb\Billing;

$user = Auth::user();
header('Content-Type: application/json');
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'unauthenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $planName = $_POST['plan'] ?? 'basic';
    $billing = new Billing();
    $plan = $billing->getPlan($planName);
    if (!$plan) {
        http_response_code(400);
        echo json_encode(['error' => 'plan']);
        exit;
    }
    if ((int)$plan['trial'] === 1) {
        if ((int)$user['trial_used'] === 1) {
            http_response_code(400);
            echo json_encode(['error' => 'trial_used']);
            exit;
        }
        $billing->subscribeUser((int)$user['id'], (int)$plan['id']);
        $billing->activateSubscription((int)$user['id'], $planName);
        $db = RemnWeb\DB::get();
        $db->prepare('UPDATE users SET trial_used=1 WHERE id=:i')->execute([':i' => $user['id']]);
        echo json_encode(['status' => 'trial']);
        exit;
    }
    $payment = $billing->createPayment((float)$plan['price'], 'http://localhost:8000/index.php');
    echo json_encode($payment);
    exit;
}

?>
