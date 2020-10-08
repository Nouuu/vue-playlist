<?php
require_once __DIR__ . '/../header_get.php';

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/Artist.php';

require_once __DIR__ . '/../../middleware/user.php';

$database = new Database();
$db = $database->getConnection();

$item = new Artist($db);
$item->id = isset($_GET['id']) ? $_GET['id'] : die;
$item->getArtist();


if (isset($item->name) && $item->name != null) {
    $artist = [
        'id' => $item->id,
        'name' => $item->name
    ];
    http_response_code(200);
    echo json_encode($artist);
} else {
    http_response_code(404);
    echo json_encode("Artist not found.");
}