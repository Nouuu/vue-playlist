<?php

require_once __DIR__ . '/../header_get.php';

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/AlbumList.php';

require_once __DIR__ . '/../../middleware/user.php';


$database = new Database();
$db = $database->getConnection();

$items = new AlbumList($db);

$stmt = $items->getLists();
$itemsCount = $stmt->rowCount();

if ($itemsCount > 0) {
    $listArr = [];
    $listArr["body"] = [];
    $listArr["itemCount"] = $itemsCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $e = [
            'id_list' => $row['id_list'],
            'name_list' => $row['name_list'],
            'date_creation_list' => $row['date_creation_list'],
            'user_email_fk' => $row['user_email_fk'],
            'album_count' => $row['album_count']
        ];
        array_push($listArr["body"], $e);
    }
    echo json_encode($listArr);
} else {
    http_response_code(404);
    echo json_encode(
        ["message" => "No record found."]
    );
}