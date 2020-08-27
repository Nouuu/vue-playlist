<?php
require_once __DIR__ . '/../header_post.php';

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/User.php';

$database = new Database();
$db = $database->getConnection();

$item = new User($db);

$data = json_decode(file_get_contents('php://input', true));

$item->email_user = $data->email_user;
$item->password_user = hash('sha256', $data->password_user);

$token = $item->login();

echo json_encode($_SESSION['token_user']);
