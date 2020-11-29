<?php
require_once __DIR__ . '/../class/User.php';
require_once __DIR__ . '/../config/Database.php';

$item = new User(null);

require __DIR__ . '/user.php';


if ($item->role_user !== 'admin') {
    $item->logout();
    http_response_code(403);
    echo json_encode(
        ['message' => 'You are not admin !']
    );
    die;

}
