<?php

require_once __DIR__ . '/../header_get.php';

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/User.php';

require_once __DIR__ . '/../../middleware/user.php';

$database = new Database();
$db = $database->getConnection();

$items = new User($db);

$stmt = $items->getUsers();
$itemsCount = $stmt->rowCount();

if ($itemsCount > 0) {
    $userArr = [];
    $userArr["body"] = [];
    $userArr["itemCount"] = $itemsCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $e = [
            'email_user' => $row['email_user'],
            'pseudo_user' => $row['pseudo_user'],
//            'password_user' => $row['password_user'],
            'role_user' => $row['role_user'],
            'date_inscription_user' => $row['date_inscription_user'],
            'list_count' => $row['list_count']
        ];
        array_push($userArr["body"], $e);
    }
    echo json_encode($userArr);
} else {
    http_response_code(404);
    echo json_encode(
        ["message" => "No record found."]
    );
}