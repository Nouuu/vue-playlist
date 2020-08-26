<?php
require_once __DIR__ . '/../header_get.php';

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/AlbumList.php';

$database = new Database();
$db = $database->getConnection();

$item = new AlbumList($db);
$item->id_list = isset($_GET['id']) ? $_GET['id'] : die();
$item->getSingleList();


if ($item->name_list != null) {
    $user = [
        'id_list' => $item->id_list,
        'name_list' => $item->name_list,
        'date_creation_list' => $item->date_creation_list,
        'user_email_fk' => $item->user_email_fk,
    ];
    http_response_code(200);
    echo json_encode($user);
} else {
    http_response_code(404);
    echo json_encode("List not found.");
}