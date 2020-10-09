<?php

require_once __DIR__ . '/AlbumList.php';
require_once __DIR__ . '/Album.php';

class AlbumInList
{
    private PDO $conn;

    private string $db_table = 'album_in_list';

    public int $album_id;
    public Album $album;
    public int $list_id;
    public AlbumList $list;
    public $grade;
    public $note;

    /**
     * AlbumInList constructor.
     * @param PDO $conn
     */
    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function addAlbumInList()
    {

    }

    public function updateAlbumInList()
    {

    }

    public function exist(): bool
    {
        $sql = 'select grade, note from ' . $this->db_table .
            ' where album_id = :album_id and list_id = :list_id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':album_id', $this->album_id);
        $stmt->bindParam(':list_id', $this->list_id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            if ($data['grade'] != $this->grade || $data['note'] != $this->note) {
                $this->updateAlbumInList();
            }
            return true;
        }
        return false;
    }


}