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

        $this->artist = new Artist($this->conn);
        if ($this->artist_id) {
            $this->artist->id = $this->artist_id;
            $this->artist->getArtist();
        } else {
            $this->artist->id = -1;
            $this->artist->name = '';
        }
    }

    public function createAlbum()
    {
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
}