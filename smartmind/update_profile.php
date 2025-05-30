<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("inc/connection.php");

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];

    if (empty($name) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Name and email are required.']);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit();
    }

    // UPDATE statement instead of SELECT
    $stmt = $con->prepare("UPDATE logintb SET name = ?, email = ?, dob = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $dob, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully!', 'name' => $name, 'email' => $email, 'dob' => $dob]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating profile: ' . $stmt->error]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
