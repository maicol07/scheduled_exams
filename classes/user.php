<?php
include('password.php');

class User extends Password
{

    private $_db;

    function __construct($db)
    {
        parent::__construct();

        $this->_db = $db;
    }

    public function login($username, $password)
    {

        $row = $this->get_user_hash($username);

        if ($this->password_verify($password, $row['password']) == 1) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['userID'] = $row['userID'];
            return true;
        }
    }

    private function get_user_hash($username)
    {

        try {
            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $stmt = $this->_db->prepare('SELECT password, email, userID FROM users WHERE email = :email AND active="Yes" ');
                $stmt->execute(array('email' => $username));
            } else {
                $stmt = $this->_db->prepare('SELECT password, username, userID FROM users WHERE username = :username AND active="Yes" ');
                $stmt->execute(array('username' => $username));
            }
            return $stmt->fetch();

        } catch (PDOException $e) {
            echo '<p class="bg-danger">' . $e->getMessage() . '</p>';
        }
    }

    public function logout()
    {
        session_destroy();
    }

    public function is_logged_in()
    {
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            return true;
        }
    }

    public function get_data()
    {
        $query = $this->_db->prepare("SELECT * FROM users WHERE userID = :userID");
        $query->execute(array('userID' => $_SESSION["userID"]));
        return $query->fetch();
    }

    public function get_user_data($username)
    {
        $query = $this->_db->prepare("SELECT * FROM users WHERE username = :username");
        $query->execute(array('username' => $username));
        return $query->fetch();
    }

    public function get_class_data($id)
    {
        $query = $this->_db->prepare("SELECT * FROM classi WHERE ID = :id");
        $query->execute(array("id" => $id));
        return $query->fetch();
    }

    public function get_list_data($id)
    {
        $query = $this->_db->prepare("SELECT * FROM liste WHERE ID = :id");
        $query->execute(array("id" => $id));
        return $query->fetch();
    }

}


?>
