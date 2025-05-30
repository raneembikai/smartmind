<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Thank You - SmartMind</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-green-100 min-h-screen flex items-center justify-center font-sans">

  <div class="bg-white p-10 rounded-2xl shadow-lg max-w-2xl text-center">
    <h1 class="text-4xl font-bold text-blue-700 mb-4">🎓 Congratulations!</h1>
    <p class="text-lg text-gray-700 mb-6">
      You have completed all 3 training levels of <strong>SmartMind</strong>!<br>
      We hope we helped you sharpen your mind and reach your potential. 🧠
    </p>

    <p class="text-md text-gray-600 mb-6">
      You can now explore our <strong>Games</strong> to keep practicing or chat with your AI coach friend for more advice and challenges or you can test your skills in computer .
    </p>

    <div class="flex flex-wrap justify-center gap-4">
      <a href="profile.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full transition">Go to Profile</a>
      <a href="gamesPro.php" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-full transition">Play Games</a>
      <a href="logic_trivia.php" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-full transition">Computer Skills</a>
        <a href="additional_training.php" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-full transition">Computer Skills</a>
    </div>
  </div>

</body>
</html>