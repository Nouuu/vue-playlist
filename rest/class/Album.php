<?php
require_once __DIR__ . '/Artist.php';

class Album
{
    private PDO $conn;

    private string $db_table = 'album';

    public int $id;
    public int $artist_id;
    public Artist $artist;
    public string $title;
    public int $year;
    public string $image;
    public int $tracks;

    /**
     * Album constructor.
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }


}