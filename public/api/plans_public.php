<?php
require __DIR__ . '/../../vendor/autoload.php';

use RemnWeb\DB;

header('Content-Type: application/json');
$plans = DB::get()->query('SELECT name, price, trial FROM plans')->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($plans);
