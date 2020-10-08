<?php
require_once __DIR__ . '/../header_post.php';

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../class/User.php';

require_once __DIR__ . '/../../middleware/user.php';

die;

$database = new Database();
$db = $database->getConnection();

$item = new User($db);

$data = json_decode(file_get_contents('php://input'));

$item->email_user = $data->email_user;

if ($item->deleteUser()) {
    echo json_encode('User deleted successfully.');
} else {
    http_response_code(500);
    echo json_encode('User could not be deleted.');
}