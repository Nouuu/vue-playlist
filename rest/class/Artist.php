<?php


class Artist
{
    private PDO $conn;

    private $db_table = 'artist';

    public int $id;
    public string $name;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getArtists()
    {
        $sql = 'select id, name from ' . $this->db_table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function getArtist()
    {
        $sql = 'select name from ' . $this->db_table . ' where id = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->name = $data['name'];
        }
    }

    public function exist()
    {
        $sql = 'select name from ' . $this->db_table .
            ' where id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            if ($data['name'] != $this->name) {
                $this->updateArtist();
            }
            return true;
        }
        return false;
    }

    public function createArtist()
    {
        if ($this->exist()) {
            return true;
        }
        $sql = 'insert into ' . $this->db_table . ' (id, name) ' .
            'VALUES (:id, :name)';

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateArtist()
    {
        $sql = 'update ' . $this->db_table . ' set ' .
            'name = :name ' .
            'where id = :id';

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteArtist()
    {
        $sql = 'delete from ' . $this->db_table . ' where id = ?';
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}