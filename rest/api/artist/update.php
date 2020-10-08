<?php
require_once __DIR__ . '/../header_post.php';
include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/Artist.php';

//require_once __DIR__ . '/../../middleware/user.php';

$database = new Database();
$db = $database->getConnection();

$item = new Artist($db);

$data = json_decode(file_get_contents('php://input'));

$item->id = $data->id;
$item->name = $data->name;

if ($item->updateArtist()) {
    echo json_encode('Artist updated successfully.');
} else {
    http_response_code(500);
    echo json_encode('Artist could not be updated.');
}