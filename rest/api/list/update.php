<?php
require_once __DIR__ . '/../header_post.php';

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/AlbumList.php';

$database = new Database();
$db = $database->getConnection();

$item = new AlbumList($db);

$data = json_decode(file_get_contents('php://input'));

$item->id_list = $data->id_list;
$item->name_list = $data->name_list;

if ($item->updateList()) {
    echo json_encode('User updated successfully.');
} else {
    echo json_encode('User could not be updated.');
}