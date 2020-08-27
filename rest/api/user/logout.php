<?php
require_once __DIR__ . '/../header_get.php';

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/User.php';

$database = new Database();
$db = $database->getConnection();

$item = new User($db);

$item->logout();
