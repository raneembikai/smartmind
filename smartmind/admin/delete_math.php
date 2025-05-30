<?php
session_start();
include 'inc/connection.php';



if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');  // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name'];  // Get admin name from session

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid request.";
    exit();
}

// Fetch the question to confirm before deletion
$query = "SELECT * FROM math_questions WHERE id = $id";
$result = mysqli_query($con, $query);
$question = mysqli_fetch_assoc($result);

if (!$question) {
    echo "Question not found!";
    exit();
}

// Perform the deletion
$delete_query = "DELETE FROM math_questions WHERE id = $id";
if (mysqli_query($con, $delete_query)) {
    header("Location: manage_math.php");
    exit();
} else {
    echo "Deletion failed!";
}
?>
