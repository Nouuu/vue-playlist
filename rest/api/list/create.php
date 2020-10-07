<?php
require_once __DIR__ . '/../header_post.php';

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/AlbumList.php';
include_once __DIR__ . '/../../class/User.php';

$database = new Database();
$db = $database->getConnection();

$item = new AlbumList($db);

$data = json_decode(file_get_contents('php://input'));
$connectedUser = new User($db);
$connectedUser->getConnectedUser();

$item->name_list = $data->name_list;
$item->user_email_fk = $connectedUser->email_user;

if ($item->createList()) {
    echo json_encode('List created successfully.');
} else {
    http_response_code(500);
    echo json_encode('List could not be created.');
}
