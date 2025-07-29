<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use RemnWeb\Auth;

Auth::logout();
header('Location: /');
