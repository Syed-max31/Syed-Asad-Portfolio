<?php
// Controllers are responsible for accepting the input, checking for validation, and triggering the Model actions.
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Contact.php';

class ContactController {
    
    // Process form submission
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Get database connection
            $database = new Database();
            $db = $database->getConnection();

            if (!$db) {
                // If connection failed, return error response.
                die('Database connection error!');
            }

            // Initialize Contact model
            $contact = new Contact($db);

            // Get POST data
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
            $message = isset($_POST['message']) ? trim($_POST['message']) : '';

            // Basic Validation Check
            if(empty($name) || empty($email) || empty($subject) || empty($message)){
                echo "Please fill in all required fields.";
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Please enter a valid email address.";
                return;
            }

            // Assign values to contact properties
            $contact->name = $name;
            $contact->email = $email;
            $contact->subject = $subject;
            $contact->message = $message;

            // Submit message to the database
            if($contact->submit()){
                // Return 'OK' because index.html's javascript validation script looks exactly for the 'OK' string
                echo "OK";
            } else{
                // Note: The js script expects an error string if submission fails.
                echo "Unable to send your message. Please try again later.";
            }
        } else {
            // Unhandled request method
            echo "Direct access not allowed.";
        }
    }
}
?>
