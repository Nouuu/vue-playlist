<?php

require_once __DIR__ . '/../header_get.php';

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/Album.php';

require_once __DIR__ . '/../../middleware/user.php';


$database = new Database();
$db = $database->getConnection();

$items = new Album($db);

$stmt = $items->getAlbums();
$itemsCount = $stmt->rowCount();

if ($itemsCount > 0) {
    $albumArr = [];
    $albumArr['body'] = [];
    $albumArr['itemCount'] = $itemsCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($albumArr['body'], Album::fromPDO($row, $db));
    }
    echo json_encode($albumArr);
} else {
    http_response_code(404);
    echo json_encode(
        ["message" => "No record found."]
    );
}
