<?php

require_once __DIR__ . '/../header_get.php';

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/Artist.php';

//require_once __DIR__ . '/../../middleware/user.php';


$database = new Database();
$db = $database->getConnection();

$items = new Artist($db);

$stmt = $items->getArtists();
$itemsCount = $stmt->rowCount();

if ($itemsCount > 0) {
    $artistArr = [];
    $artistArr['body'] = [];
    $artistArr['itemCount'] = $itemsCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($artistArr['body'],
            [
                'id' => $row['id'],
                'name' => $row['name']
            ]);
    }
    echo json_encode($artistArr);
} else {
    http_response_code(404);
    echo json_encode(
        ["message" => "No record found."]
    );
}
