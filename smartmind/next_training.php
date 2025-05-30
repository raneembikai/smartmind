// next_level.php
<?php
session_start();
include("inc/connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get the user's current level
$stmt = $con->prepare("SELECT level FROM logintb WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($level);
$stmt->fetch();
$stmt->close();

// Provide content based on the user's level
switch ($level) {
    case 2:
        // Next level content (level 2 training)
        echo "<h1>Welcome to Level 2 Training!</h1>";
        break;
    case 3:
        // Next level content (level 3 training)
        echo "<h1>Welcome to Level 3 Training!</h1>";
        break;
    default:
        // Default training content for level 1
        echo "<h1>Welcome to Level 1 Training!</h1>";
        break;
}
?>
