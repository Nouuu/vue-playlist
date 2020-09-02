<?php
require_once __DIR__ . '/../header_post.php';

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/AlbumList.php';

$database = new Database();
$db = $database->getConnection();

$item = new AlbumList($db);

$data = json_decode(file_get_contents('php://input'));

$item->id_list = $data->id_list;

if ($item->deleteList()) {
    echo json_encode('List deleted successfully.');
} else {
    http_response_code(500);
    echo json_encode('List could not be deleted.');
}