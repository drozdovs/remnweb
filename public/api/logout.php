<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';

use RemnWeb\Auth;

Auth::logout();
header('Content-Type: application/json');
echo json_encode(['status' => 'ok']);
