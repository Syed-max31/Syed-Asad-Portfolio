<?php
class Database {
    private $host = "localhost";
    private $db_name = "iportfolio_db"; // Make sure your database name matches this
    private $username = "root";         // Your DB username (default for XAMPP is 'root')
    private $password = "";             // Your DB password (default for XAMPP is empty)
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Ideally suppress the message in production:
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
