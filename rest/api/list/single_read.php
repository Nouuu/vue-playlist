<?php
require_once __DIR__ . '/../header_get.php';

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/AlbumList.php';

//require_once __DIR__ . '/../../middleware/user.php';

$database = new Database();
$db = $database->getConnection();

$item = new AlbumList($db);
$item->id_list = isset($_GET['id']) ? $_GET['id'] : die();

$item->getSingleList();
$item->getAlbumInList();


if (isset($item->name_list) && $item->name_list != null) {
    http_response_code(200);
    echo json_encode($item);
} else {
    http_response_code(404);
    echo json_encode("List not found.");
}