<?php
require __DIR__ . '/../../tools/dbTools.php';

if (empty($_GET['email']) || !is_string($_GET['email'])) {
    header($_SERVER["SERVER_PROTOCOL"] . ' 403 Forbiden', true, 403);
    die;
}


$email = strtolower(trim($_GET['email']));

echo json_encode(dbTools::getUser($email));