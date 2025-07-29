<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';

use RemnWeb\Admin;

Admin::logout();
header('Content-Type: application/json');
echo json_encode(['status' => 'ok']);
