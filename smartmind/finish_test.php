<?php
session_start();
include("inc/connection.php");

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Unauthorized";
    exit();
}

$user_id = $_SESSION['user_id'];


// Set test_taken = 1
$stmt = $con->prepare("UPDATE logintb SET test_taken = 1 WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();
 



echo "Test marked as completed";
?>
