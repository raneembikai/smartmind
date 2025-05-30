<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("inc/connection.php");
$user_id = $_SESSION['user_id'];

// Fetch updated name and level
$stmt = $con->prepare("SELECT name, level FROM logintb WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $level);
$stmt->fetch();
$stmt->close();

// Debugging: Check if the user level is fetched correctly
error_log("User Level before update: " . $level);

function get_last_score($user_id, $category, $con) {
    $stmt = $con->prepare("SELECT score FROM user_scores WHERE user_id = ? AND subject = ? ORDER BY timestamp DESC LIMIT 1");
    $stmt->bind_param("is", $user_id, $category);
    $stmt->execute();
    $stmt->bind_result($last_score);
    $stmt->fetch();
    $stmt->close();
    return $last_score;
}

$math_score = get_last_score($user_id, 'Math', $con);
$pattern_score = get_last_score($user_id, 'Pattern', $con);
$deduction_score = get_last_score($user_id, 'Deduction', $con);

// Check if all categories are completed
$all_completed = ($math_score !== null && $pattern_score !== null && $deduction_score !== null);

// Debugging: Check if all categories are completed
error_log("All categories completed: " . ($all_completed ? 'Yes' : 'No'));

// Function to update user level
function update_user_level($user_id, $con) {
    // Debugging: log the update process
    error_log("Updating user level for user ID: " . $user_id);
    
    $stmt = $con->prepare("UPDATE logintb SET level = level + 1 WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// If all categories completed, upgrade level
if ($all_completed) {
    $max_level = 3; // Adjust this as per your system's requirements
    if ($level < $max_level) {
        update_user_level($user_id, $con);
        
        // Debugging: Check if the level was updated in the database
        error_log("Level updated for user ID: " . $user_id);
        
        // Clear scores for next level
        $clear = $con->prepare("DELETE FROM user_scores WHERE user_id = ?");
        $clear->bind_param("i", $user_id);
        $clear->execute();
        $clear->close();

        $_SESSION['level_up'] = $level + 1;

        // Redirect to start training page after a short delay
        header("Refresh: 2; url=start_training.php");
        exit;
    } elseif ($level == $max_level) {
        header("Location: thank_you.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logic Training - SmartMind</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen flex items-center justify-center font-sans">

    <div class="bg-white p-10 rounded-2xl shadow-2xl w-full max-w-3xl text-center">
        <!-- User Info -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Welcome, <span class="text-blue-600"><?= htmlspecialchars($name) ?></span>!</h2>
            <p class="text-md text-gray-600">🎯 Current Level: <span class="text-green-600 font-bold"><?= htmlspecialchars($level) ?></span></p>
        </div>

        <!-- Main Title -->
        <h1 class="text-4xl font-bold text-blue-800 mb-4">Train Your Logic 🧠</h1>
        <p class="text-lg text-gray-700 mb-6">
            Want to boost your brainpower? These logic challenges are just for you!
        </p>

        <!-- Start Button -->
        <a href="start_training.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full shadow-md transition duration-300 text-xl">
            Start Training Now
        </a>

        <p class="mt-6 text-sm text-gray-600 italic">Keep practicing and grow stronger mentally 💪</p>
    </div>

</body>
</html>