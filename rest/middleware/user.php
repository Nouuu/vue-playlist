<?php
require_once __DIR__ . '/../class/User.php';
require_once __DIR__ . '/../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$item = new User($db);

if (!$item->isConnected()) {
    http_response_code(401);
    echo json_encode(
        ['message' => 'User not connected :']
    );
    die;
}
$item->getConnectedUser();

if (empty($item->email_user)) {
    $item->logout();
    http_response_code(401);
    echo json_encode(
        ['message' => 'Token expired']
    );
    die;
}
