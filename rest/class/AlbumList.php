<?php
require_once __DIR__ . '/AlbumInList.php';

class AlbumList
{
    private PDO $conn;

    private $db_table = 'list';

    public int $id_list;
    public $name_list;
    public $date_creation_list;
    public $user_email_fk;
    public int $album_count;
    public $image_list;
    public array $albums_in_list;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getLists()
    {
        $sql = 'select id_list, name_list, date_creation_list, user_email_fk,' .
            ' (select count(*) from album_in_list where list_id_fk = id_list) as album_count from ' . $this->db_table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function getUsersLists()
    {
        $sql = 'select id_list, name_list, date_creation_list, user_email_fk, cover,' .
            ' (select count(*) from album_in_list where list_id = id_list) as album_count from ' . $this->db_table .
            ' where user_email_fk = ?';
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(1, $this->user_email_fk);

        $stmt->execute();
        return $stmt;
    }

    public function createList()
    {
        $sql = 'insert into ' . $this->db_table . ' (name_list, date_creation_list, user_email_fk, cover) ' .
            'VALUES (:name, CURDATE(), :user_email, :cover)';

        $stmt = $this->conn->prepare($sql);

        $this->name_list = htmlspecialchars(strip_tags($this->name_list));
        $this->user_email_fk = htmlspecialchars(strip_tags($this->user_email_fk));

        $stmt->bindParam(':name', $this->name_list);
        $stmt->bindParam(':user_email', $this->user_email_fk);
        $stmt->bindParam(':cover', $this->image_list);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getSingleList()
    {
        $sql = 'select id_list, name_list, date_creation_list, user_email_fk,' .
            ' (select count(*) from album_in_list where list_id = id_list) as album_count' .
            ' from ' . $this->db_table .
            ' where id_list = ? limit 0,1';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $this->id_list);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->name_list = $data['name_list'];
            $this->date_creation_list = $data['date_creation_list'];
            $this->user_email_fk = $data['user_email_fk'];
            $this->album_count = $data['album_count'];
        }
    }

    public function updateList()
    {
        $sql = 'update ' . $this->db_table . ' set ' .
            'name_list = :name, ' .
            'cover = :cover ' .
            'where id_list = :id';

        $stmt = $this->conn->prepare($sql);

        $this->name_list = htmlspecialchars(strip_tags($this->name_list));

        $stmt->bindParam(':id', $this->id_list);
        $stmt->bindParam(':name', $this->name_list);
        $stmt->bindParam(':cover', $this->image_list);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteList()
    {
        $sql = 'delete from ' . $this->db_table . ' where id_list = ?';
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(1, $this->id_list);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function isOwner(): bool
    {
        $sql = 'select count(*) as owning from ' . $this->db_table .
            ' where id_list = :id_list AND user_email_fk = :email';
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':id_list', $this->id_list);
        $stmt->bindParam(':email', $this->user_email_fk);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return $data['owning'] == 1;
        }
        return false;
    }

    public function getAlbumInList()
    {
        $sql = 'select album_id ' .
            'from album_in_list ' .
            'where list_id = ?';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $this->id_list);
        $stmt->execute();

        $final = [];

        if ($stmt->rowCount()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $album_in_list = new AlbumInList($this->conn);
                $album_in_list->list_id = $this->id_list;
                $album_in_list->album_id = $row['album_id'];
                $album_in_list->getAlbumInList();

                array_push($final, $album_in_list);
            }
        }
        $this->albums = $final;
    }
}