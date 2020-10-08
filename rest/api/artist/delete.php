<?php
require_once __DIR__ . '/../header_post.php';
include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/Artist.php';

require_once __DIR__ . '/../../middleware/admin.php';

$database = new Database();
$db = $database->getConnection();

$item = new Artist($db);

$data = json_decode(file_get_contents('php://input'));

$item->id = $data->id;

if ($item->deleteArtist()) {
    echo json_encode('Artist deleted successfully.');
} else {
    http_response_code(500);
    echo json_encode('Artist could not be deleted.');
}