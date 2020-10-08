<?php
require_once __DIR__ . '/../header_post.php';

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/AlbumList.php';
include_once __DIR__ . '/../../class/User.php';

require_once __DIR__ . '/../../middleware/user.php';

$database = new Database();
$db = $database->getConnection();

$item = new AlbumList($db);

$data = json_decode(file_get_contents('php://input'));

$connectedUser = new User($db);
$connectedUser->getConnectedUser();


$item->id_list = $data->id_list;
$item->name_list = $data->name_list;
$item->user_email_fk = $connectedUser->email_user;

if ($item->isOwner()) {
    if ($item->updateList()) {
        echo json_encode('User updated successfully.');
    } else {
        echo json_encode('User could not be updated.');
    }
} else {
    http_response_code(403);
    echo json_encode('You are not the owner');
}