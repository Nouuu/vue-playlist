<?php
require_once __DIR__ . '/../header_post.php';

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/User.php';

require_once __DIR__ . '/../../middleware/user.php';

$database = new Database();
$db = $database->getConnection();

$item = new User($db);

$data = json_decode(file_get_contents('php://input'));

$item->email_user = $data->email_user;
$item->pseudo_user = $data->pseudo_user;
$item->password_user = hash('sha256', $data->password_user);
$item->role_user = $data->role_user;

if ($item->updateUser()) {
    echo 'User updated successfully.';
} else {
    echo 'User could not be updated.';
}