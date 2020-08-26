<?php
require_once __DIR__ . '/../header_post.php';

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/User.php';

$database = new Database();
$db = $database->getConnection();

$item = new User($db);

$data = json_decode(file_get_contents('php://input'));

$item->email_user = $data->email_user;
$item->pseudo_user = $data->pseudo_user;
$item->password_user = hash('sha256', $data->password_user);
$item->role_user = $data->role_user;

if ($item->createUser()) {
    echo 'User created successfully.';
} else {
    http_response_code(500);
    echo 'User could not be created.';
}
