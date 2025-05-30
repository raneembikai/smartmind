<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['rating'], $_POST['message'])) {
        $rating = (int)$_POST['rating'];
        $message = trim($_POST['message']);

        // Get user name and email from session
        $name = isset($_SESSION['name']) ? $_SESSION['name'] : null;
        $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

        if ($name && $email) {
            // Connect to database
            $conn = new mysqli('localhost', 'root', '', 'smartmind_db');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and execute the insert statement
            $stmt = $conn->prepare("INSERT INTO feedback_reviews (name, email, rating, message, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssis", $name, $email, $rating, $message);

            if ($stmt->execute()) {
                echo "Feedback submitted successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "User session not found. Please log in.";
        }
    } else {
        echo "Rating and message are required.";
    }
} else {
    echo "Invalid request method.";
}
?>
