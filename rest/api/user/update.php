<?php
require_once __DIR__ . '/../header_post.php';

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/User.php';

require_once __DIR__ . '/../../middleware/user.php';


$database = new Database();
$db = $database->getConnection();

$item = new User($db);

$connectedUser = new User($db);
$connectedUser->getConnectedUser();

$data = json_decode(file_get_contents('php://input'));

$item->email_user = $connectedUser->email_user;
$item->pseudo_user = $data->pseudo_user;
$item->password_user = hash('sha256', $data->password_user);

if ($item->updateUser()) {
    echo json_encode('User updated successfully.');
} else {
    echo json_encode('User could not be updated.');
}