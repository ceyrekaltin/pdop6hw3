<?php
require_once("db.php");

class User {
    private $dbh;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function registerUser($naam, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        return $this->dbh->execute(
            "INSERT INTO user (naam, email, password) VALUES (:naam, :email, :password)",
            ["naam" => $naam, "email" => $email, "password" => $hash]
        );
    }

    public function loginUser($email, $password) {
        $stmt = $this->dbh->execute("SELECT * FROM user WHERE email = :email", ["email" => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Start een sessie
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = $user['userid'];
            $_SESSION['logged_in'] = true;


            return true;
        }
        return false;
    }
}       
?>
