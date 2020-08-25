<?php
header("Access-Control-Allow-Origin: *");

require '../vendor/autoload.php';
require '../env.php';

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
        $database = self::getdbtool();
        $data = $database->select('album', ['id', 'name', 'artist', 'tracks', 'duration']);

        if ($database->error()[0] !== '00000') {
            header($_SERVER["SERVER_PROTOCOL"] . ' 500 Internal Server Error', true, 500);
            print_r($database->error());
            die;
        }
        return $data;
    }
}