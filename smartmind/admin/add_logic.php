<?php

session_start();
include 'inc/connection.php';



if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');  // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name'];  // Get admin name from session
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $con->prepare("INSERT INTO math_questions (question_text, option_a, option_b, option_c, option_d, correct_option, age_level, iq_level) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssii", $_POST['question_text'], $_POST['option_a'], $_POST['option_b'], $_POST['option_c'], $_POST['option_d'], $_POST['correct_option'], $_POST['age_level'], $_POST['iq_level']);
    $stmt->execute();
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Math Question - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">

  <!-- Main content container -->
  <main class="bg-gray-800 p-8 rounded-lg shadow-xl w-full sm:w-11/12 md:w-8/12 lg:w-6/12 xl:w-5/12">
    <h1 class="text-3xl font-bold text-white text-center mb-6"> Add New Math Question</h1>
    
    <!-- Back Button with blue color -->
    <a href="admin.php" class="text-white bg-blue-600 hover:bg-blue-700 rounded-lg px-6 py-3 mb-6 inline-block transition duration-300">
      ← Back to Admin
    </a>

    <?php if ($success): ?>
      <div class="bg-blue-100 text-blue-800 p-4 rounded mb-6">✅ Question added successfully.</div>
    <?php endif; ?>

    <!-- Form for adding new question -->
    <form method="POST" class="space-y-6">
      <div>
        <label for="question_text" class="block text-lg font-medium text-white">Question</label>
        <input type="text" name="question_text" id="question_text" placeholder="Enter question text" required class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white placeholder-gray-400">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="option_a" class="block text-lg font-medium text-white">Option A</label>
          <input type="text" name="option_a" id="option_a" placeholder="Option A" required class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white placeholder-gray-400">
        </div>
        <div>
          <label for="option_b" class="block text-lg font-medium text-white">Option B</label>
          <input type="text" name="option_b" id="option_b" placeholder="Option B" required class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white placeholder-gray-400">
        </div>
        <div>
          <label for="option_c" class="block text-lg font-medium text-white">Option C</label>
          <input type="text" name="option_c" id="option_c" placeholder="Option C" required class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white placeholder-gray-400">
        </div>
        <div>
          <label for="option_d" class="block text-lg font-medium text-white">Option D</label>
          <input type="text" name="option_d" id="option_d" placeholder="Option D" required class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white placeholder-gray-400">
        </div>
      </div>

      <div>
        <label for="correct_option" class="block text-lg font-medium text-white">Correct Option (A/B/C/D)</label>
        <input type="text" name="correct_option" id="correct_option" placeholder="Correct Option" maxlength="1" required class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white placeholder-gray-400">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="age_level" class="block text-lg font-medium text-white">Select Age Level</label>
          <select name="age_level" id="age_level" required class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white">
            <option value="">Select Age Level</option>
            <option value="1">Level 1 (Age 7–13)</option>
            <option value="2">Level 2 (Age 14–18)</option>
            <option value="3">Level 3 (Age 19+)</option>
          </select>
        </div>

        <div>
          <label for="iq_level" class="block text-lg font-medium text-white">Select IQ Level</label>
          <select name="iq_level" id="iq_level" required class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white">
            <option value="">Select IQ Level</option>
            <option value="1">Level 1 (IQ ≤ 65)</option>
            <option value="2">Level 2 (IQ 66–85)</option>
            <option value="3">Level 3 (IQ ≥ 86)</option>
          </select>
        </div>
      </div>

      <!-- Add Question Button with blue color -->
      <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 w-full">
        Add Question
      </button>
    </form>
  </main>
</body>
</html>
