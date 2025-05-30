<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "smartmind_db");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the level parameter from the URL (default to 1 if not provided)
$level = isset($_GET['level']) ? (int)$_GET['level'] : 1;

// Fetch 20 random questions based on the level from the database
$sql = "SELECT * FROM iqquestions WHERE level = $level ORDER BY RAND() LIMIT 20";
$result = mysqli_query($con, $sql);

$questions = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $questions[] = $row; // Add each question to the array
    }
}

// Return the questions as a JSON array
header('Content-Type: application/json');
echo json_encode($questions);

// Close the database connection
mysqli_close($con);
?>
