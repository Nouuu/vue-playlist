<?php
require_once __DIR__ . '/../header_get.php';

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/Album.php';

//require_once __DIR__ . '/../../middleware/user.php';

$database = new Database();
$db = $database->getConnection();

$item = new Album($db);
$item->id = isset($_GET['id']) ? $_GET['id'] : die;
$item->getAlbum();


if (isset($item->title) && $item->title != null) {

    http_response_code(200);
    echo json_encode($item);
} else {
    http_response_code(404);
    echo json_encode("Album not found.");
}