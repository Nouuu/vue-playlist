<?php
require_once __DIR__ . '/Artist.php';

class Album
{
    private PDO $conn;

    private string $db_table = 'album';

    public int $id;
    public $artist_id;
    public Artist $artist;
    public string $title;
    public $year;
    public string $image;
    public $tracks;

    /**
     * Album constructor.
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public static function fromPDO($row, PDO $db): Album
    {
        $album = new Album($db);

        $album->artist_id = empty($row['artist_id']) ? null : $row['artist_id'];
        $album->title = empty($row['title']) ? '' : $row['title'];
        $album->year = empty($row['year']) ? null : $row['year'];
        $album->image = empty($row['image']) ? '' : $row['image'];
        $album->tracks = empty($row['tracks']) ? null : $row['tracks'];

        if ($album->artist_id) {
            $album->artist = new Artist($db);
            $album->artist->id = (int)$album->artist_id;
            $album->artist->getArtist();
        }

        return $album;
    }


    public function getAlbums()
    {
        $sql = 'select id, artist_id, title, year, image, tracks from ' . $this->db_table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function getAlbum()
    {
        $sql = 'select id, artist_id, title, year, image, tracks from ' . $this->db_table
            . ' where id = ? limit 0,1';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->artist_id = $data['artist_id'];
            $this->title = $data['title'];
            $this->year = $data['year'];
            $this->image = $data['image'];
            $this->tracks = $data['tracks'];
        }

        if ($this->artist_id) {
            $this->artist = new Artist($this->conn);
            $this->artist->id = $this->artist_id;
            $this->artist->getArtist();
        }
    }

    public function createAlbum()
    {
        if ($this->exist()) {
            return true;
        }

        $sql = 'insert into ' . $this->db_table . ' (id, artist_id, title, year, image, tracks)' .
            ' VALUES (:id, :artist_id, :title, :year, :image, :tracks)';
        $stmt = $this->conn->prepare($sql);

        $this->title = htmlspecialchars(strip_tags($this->title));

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':artist_id', $this->artist_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':tracks', $this->tracks);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateAlbum()
    {
        $sql = 'update ' . $this->db_table . ' set ' .
            'artist_id = :artist_id, ' .
            'title = :title, ' .
            'year = :year, ' .
            'image = :image, ' .
            'tracks = :tracks ' .
            'where id = :id';
        $stmt = $this->conn->prepare($sql);

        $this->title = htmlspecialchars(strip_tags($this->title));

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':artist_id', $this->artist_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':tracks', $this->tracks);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteAlbum()
    {
        $sql = 'delete from ' . $this->db_table . ' where id = ?';
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(1, $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function exist():bool
    {
        $sql = 'select title from ' . $this->db_table .
            ' where id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            if ($data['title'] != $this->title) {
                $this->updateAlbum();
            }
            return true;
        }
        return false;
    }
}