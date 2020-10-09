<?php
require_once __DIR__ . '/../header_post.php';
include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/AlbumInList.php';
include_once __DIR__ . '/../../class/User.php';

//require_once __DIR__ . '/../../middleware/user.php';

$database = new Database();
$db = $database->getConnection();

$item = new AlbumInList($db);

$data = json_decode(file_get_contents('php://input'));

$item->album_id = empty($data->album_id) ? die : $data->album_id;
$item->list_id = empty($data->list_id) ? die : $data->list_id;
$item->grade = empty($data->grade) ? null : $data->grade;
$item->note = empty($data->note) ? null : $data->note;

$connectedUser = new User($db);
$connectedUser->getConnectedUser();

//if ($item->isOwner($connectedUser->email_user)) {
if ($item->updateAlbumInList()) {
    echo json_encode('Album updated to list successfully.');
} else {
    http_response_code(500);
    echo json_encode('Album updated not be added to list.');
}
//} else {
//    http_response_code(403);
//    echo json_encode('You are not the owner');
//}
