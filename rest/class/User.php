<?php


class User
{
    private PDO $conn;

    private $db_table = 'user';

    public $email_user;
    public $pseudo_user;
    public $password_user;
    public $role_user;
    public $date_inscription_user;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getUsers()
    {
        $sql = 'select email_user, pseudo_user, password_user, role_user, date_inscription_user from ' . $this->db_table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function createUser()
    {
        $sql = 'insert into ' . $this->db_table . ' ' .
            '(email_user, pseudo_user, password_user, role_user, date_inscription_user) ' .
            'VALUES ' .
            '(:email,:pseudo,:password,:role,CURDATE())';

        $stmt = $this->conn->prepare($sql);

        $this->email_user = htmlspecialchars(strip_tags($this->email_user));
        $this->pseudo_user = htmlspecialchars(strip_tags($this->pseudo_user));
        $this->password_user = htmlspecialchars(strip_tags($this->password_user));
        $this->role_user = htmlspecialchars(strip_tags($this->role_user));

        $stmt->bindParam(':email', $this->email_user);
        $stmt->bindParam(':pseudo', $this->pseudo_user);
        $stmt->bindParam(':password', $this->password_user);
        $stmt->bindParam(':role', $this->role_user);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getSingleUser()
    {
        $sql = "select email_user, pseudo_user, password_user, role_user, date_inscription_user " .
            "from " . $this->db_table . " where email_user = ? limit 0,1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $this->email_user);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->pseudo_user = $data['pseudo_user'];
            $this->password_user = $data['password_user'];
            $this->role_user = $data['role_user'];
            $this->date_inscription_user = $data['date_inscription_user'];
        }

    }

    public function updateUser()
    {
        $sql = 'update ' . $this->db_table . ' set ' .
            'pseudo_user = :pseudo, ' .
            'password_user = :password, ' .
            'role_user = :role ' .
            'where email_user = :email';

        $stmt = $this->conn->prepare($sql);

        $this->email_user = htmlspecialchars(strip_tags($this->email_user));
        $this->pseudo_user = htmlspecialchars(strip_tags($this->pseudo_user));
        $this->password_user = htmlspecialchars(strip_tags($this->password_user));
        $this->role_user = htmlspecialchars(strip_tags($this->role_user));

        $stmt->bindParam(':email', $this->email_user);
        $stmt->bindParam(':pseudo', $this->pseudo_user);
        $stmt->bindParam(':password', $this->password_user);
        $stmt->bindParam(':role', $this->role_user);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteUser()
    {
        $sql = 'delete from ' . $this->db_table . ' where email_user = ?';
        $stmt = $this->conn->prepare($sql);

        $this->email_user = htmlspecialchars(strip_tags($this->email_user));
        $stmt->bindParam(1, $this->email_user);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}