<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("inc/connection.php");
$user_id = $_SESSION['user_id'];

// Fetch user name only
$stmt = $con->prepare("SELECT name FROM logintb WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name);
$stmt->fetch();
$stmt->close();
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
        </div>

        <!-- Main Title -->
        <h1 class="text-4xl font-bold text-blue-800 mb-4">Train Your Logic 🧠</h1>
        <p class="text-lg text-gray-700 mb-6">
            Select a category below and start training your logic skills right away!
        </p>

        <!-- Categories -->
        <div class="flex flex-wrap justify-center gap-6">
            <a href="math_training.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-md transition duration-300">
                Math Logic
            </a>
            <a href="pattern_training.php" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-full shadow-md transition duration-300">
                Pattern Recognition
            </a>
            <a href="deduction_training.php" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-full shadow-md transition duration-300">
                Deductive Reasoning
            </a>
           
             <a href="profile.php"  class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-md transition duration-300">
                Back home
            </a>
           
        </div>

        <p class="mt-8 text-sm text-gray-600 italic">Practice any category anytime. Your brain, your choice! 💪</p>
    </div>

</body>
</html>
