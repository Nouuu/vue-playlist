<?php
header("Access-Control-Allow-Origin: *");

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . '/../env.php';

use Medoo\Medoo;

trait dbTools
{
    public static function getdbtool(): Medoo
    {
        try {
            return new Medoo([
                'database_type' => env_database_type,
                'database_name' => env_database_name,
                'server' => env_server,
                'username' => env_username,
                'password' => env_password
            ]);

        } catch (Exception $e) {
            header($_SERVER["SERVER_PROTOCOL"] . ' 500 Internal Server Error', true, 500);
            print_r($e->getMessage());
            print_r($e->getCode());
            die;
        }
    }

    public static function getAlbums()
    {
//        $database = self::getdbtool();
//        $data = $database->select('album', ['id_album', 'name', 'artist', 'tracks', 'duration']);
//
//        if ($database->error()[0] !== '00000') {
//            header($_SERVER["SERVER_PROTOCOL"] . ' 500 Internal Server Error', true, 500);
//            print_r($database->error());
//            die;
//        }
//        return $data;
    }

    public static function getUsersList()
    {
        $database = self::getdbtool();
        $data = $database->select('user', ['email_user', 'pseudo_user', 'password_user', 'role_user', 'date_inscription_user']);
        for ($i = 0; $i < sizeof($data); $i++) {
            $count = $database->count('list', ['user_email_fk' => $data[$i]['email_user']]);
            $data[$i]['list_count'] = $count;
        }

        self::checkError($database);
        return $data;
    }

    public static function getUser($email)
    {
        $database = self::getdbtool();
        $data = $database->select('user', ['email_user', 'pseudo_user', 'password_user', 'role_user', 'date_inscription_user'], ["email_user[=]" => $email]);

        self::checkError($database);
        self::isUnique($data);
        return $data[0];
    }

    private static function checkError($database)
    {
        if ($database->error()[0] !== '00000') {
            header($_SERVER["SERVER_PROTOCOL"] . ' 500 Internal Server Error', true, 500);
            print_r($database->error());
            die;
        }
    }

    private static function isUnique($result)
    {
        if (sizeof($result) !== 1) {
            header($_SERVER["SERVER_PROTOCOL"] . '500 Internal Server Error', true, 500);
            print_r("Value should be unique, but multiple founded");
            die;
        }
    }
}