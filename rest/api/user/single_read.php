<?php
require_once __DIR__ . '/../header_get.php';

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/User.php';

$database = new Database();
$db = $database->getConnection();

$item = new User($db);
$item->email_user = isset($_GET['email']) ? $_GET['email'] : die();

$item->getSingleUser();

if ($item->pseudo_user != null) {
    $user = [
        'email_user' => $item->email_user,
        'pseudo_user' => $item->pseudo_user,
        'password_user' => $item->password_user,
        'role_user' => $item->role_user,
        'date_inscription_user' => $item->date_inscription_user
    ];

    http_response_code(200);
    echo json_encode($user);
} else {
    http_response_code(404);
    echo json_encode("User not found.");
}