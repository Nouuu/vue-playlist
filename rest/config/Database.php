<?php
require_once __DIR__ . '/../env.php';

class Database
{
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(env_database_type
                . ':host=' . env_db_server
                . ';dbname=' . env_database_name,
                env_db_username,
                env_db_password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo json_encode('Database could not be connected: ' . $exception->getMessage());
        }
        return $this->conn;
    }
}