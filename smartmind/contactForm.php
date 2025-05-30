<?php
// Enable error reporting


// DB connection
$host = "localhost";
$db = "smartmind_db";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Sanitize inputs
$name = htmlspecialchars(trim($_POST['name']));
$email = htmlspecialchars(trim($_POST['email']));
$subject = htmlspecialchars(trim($_POST['subject']));
$message = htmlspecialchars(trim($_POST['message']));
$created_at = date("Y-m-d H:i:s");

// Insert into DB
$sql = "INSERT INTO contacts (name, email, message, created_at) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $email, $message, $created_at);
$stmt->execute();

$stmt->close();
$conn->close();
echo "Message sent successfully.";
?>