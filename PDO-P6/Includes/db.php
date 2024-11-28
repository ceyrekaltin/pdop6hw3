<?php

class DB {
    private $dbh;
    protected $stmt;

    public function __construct($servername = "localhost", $username = "root", $password = "", $db = "p6") {
        try {
            $this->dbh = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected <br>";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function execute($sql, $placeholders = []) {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($placeholders);
        return $stmt;
    }
}
?>
