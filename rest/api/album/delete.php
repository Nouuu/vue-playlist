<?php
require_once __DIR__ . '/../header_post.php';
include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/Album.php';

//require_once __DIR__ . '/../../middleware/user.php';

$database = new Database();
$db = $database->getConnection();

$item = new Album($db);

$data = json_decode(file_get_contents('php://input'));

$item->id = $data->id;

if ($item->deleteAlbum()) {
    echo json_encode('Album deleted successfully.');
} else {
    http_response_code(500);
    echo json_encode('Album could not be deleted.');
}