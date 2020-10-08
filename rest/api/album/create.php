<?php
require_once __DIR__ . '/../header_post.php';
include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/Album.php';

require_once __DIR__ . '/../../middleware/admin.php';

$database = new Database();
$db = $database->getConnection();

$item = new Album($db);

$data = json_decode(file_get_contents('php://input'));

$item->id = empty($data->id) ? die : $data->id;
$item->artist_id = empty($data->artist_id) ? null : $data->artist_id;
$item->title = empty($data->title) ? die : $data->title;
$item->year = empty($data->year) ? null : $data->year;
$item->image = empty($data->image) ? '' : $data->image;
$item->tracks = empty($data->tracks) ? null : $data->tracks;

if ($item->createAlbum()) {
    echo json_encode('Album created successfully.');
} else {
    http_response_code(500);
    echo json_encode('Album could not be created.');
}