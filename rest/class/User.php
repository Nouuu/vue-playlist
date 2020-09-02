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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->conn = $db;
    }

    public function getUsers()
    {
        $sql = 'select email_user, pseudo_user, password_user, role_user, date_inscription_user, ' .
            '(select count(*) from list where user_email_fk = email_user) as list_count from ' . $this->db_table;
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
        $sql = 'select email_user, pseudo_user, password_user, role_user, date_inscription_user ' .
            'from ' . $this->db_table . ' where email_user = ? limit 0,1';
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

    public function login()
    {
        $sql = 'select email_user, pseudo_user, password_user, role_user, date_inscription_user ' .
            'from ' . $this->db_table . ' where email_user = :email and password_user = :password limit 0,1';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam('email', $this->email_user);
        $stmt->bindParam('password', $this->password_user);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->pseudo_user = $data['pseudo_user'];
            $this->role_user = $data['role_user'];
            $this->date_inscription_user = $data['date_inscription_user'];

            return $this->saveToken();

        } else {
            http_response_code(401);
            echo json_encode('Fail to login');
            die;
        }
    }

    private function generateToken()
    {
        try {
            return substr(str_shuffle(bin2hex(random_bytes(100))), 0, 60);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode('Fail to generate token');
            die;
        }
    }

    private function saveToken()
    {
        $token = $this->generateToken();
        $_SESSION['token_user'] = $token;

        $sql = 'update ' . $this->db_table . ' set token = :token ' .
            ' where email_user = :email';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam('token', $token);
        $stmt->bindParam('email', $this->email_user);
        $stmt->execute();

        if ($stmt->errorCode() !== '00000') {
            http_response_code(500);
            echo json_encode('Fail to save token');
            die;
        }

        return $token;
    }

    public function logout()
    {
        $sql = 'update ' . $this->db_table . ' set token = null ' .
            ' where token = :token';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam('token', $token);
        $stmt->execute();

        unset($_SESSION['token_user']);
    }

    public function isConnected()
    {
        return isset($_SESSION['token_user']) && strlen($_SESSION['token_user']) === 60;
    }

    public function getConnectedUser()
    {
        if ($this->isConnected()) {
            $sql = 'select email_user, pseudo_user, password_user, role_user, date_inscription_user ' .
                'from ' . $this->db_table . ' where token = ? limit 0,1';
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $_SESSION['token_user']);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                $this->email_user = $data['email_user'];
                $this->pseudo_user = $data['pseudo_user'];
                $this->password_user = $data['password_user'];
                $this->role_user = $data['role_user'];
                $this->date_inscription_user = $data['date_inscription_user'];
            }
        }
    }
}