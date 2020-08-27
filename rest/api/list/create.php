<?php
require_once __DIR__ . '/../header_post.php';

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/AlbumList.php';

$database = new Database();
$db = $database->getConnection();

$item = new AlbumList($db);

$data = json_decode(file_get_contents('php://input'));

$item->name_list = $data->name_list;
$item->user_email_fk = $data->user_email_fk;

if ($item->createList()) {
    echo 'List created successfully.';
} else {
    http_response_code(500);
    echo 'List could not be created.';
}
