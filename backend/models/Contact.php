<?php
class Contact {
    private $conn;
    private $table_name = "messages";

    // Contact form properties
    public $name;
    public $email;
    public $subject;
    public $message;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Insert a new contact message
    public function submit() {
        // Query to insert record
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                      name = :name,
                      email = :email,
                      subject = :subject,
                      message = :message";

        // Prepare query
        $stmt = $this->conn->prepare($query);

        // Sanitize strings
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->subject = htmlspecialchars(strip_tags($this->subject));
        $this->message = htmlspecialchars(strip_tags($this->message));

        // Bind values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':subject', $this->subject);
        $stmt->bindParam(':message', $this->message);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
