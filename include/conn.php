<?php
class Connexion {

    public $host = "localhost";
    public $username = "root";
    public $password = "root";
    public $database = "toiletagecanin";
    public $conn;

    function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }
    }
}
?>