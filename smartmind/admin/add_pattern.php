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
    $image_name = basename($_FILES['question_image']['name']);
    $target_path = "pattern_images/" . $image_name;
    move_uploaded_file($_FILES['question_image']['tmp_name'], $target_path);

    $stmt = $con->prepare("INSERT INTO pattern_questions (question_image, option_a, option_b, option_c, option_d, correct_option, iq_level) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $image_name, $_POST['option_a'], $_POST['option_b'], $_POST['option_c'], $_POST['option_d'], $_POST['correct_option'], $_POST['iq_level']);
    $stmt->execute();
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Pattern Question - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black min-h-screen flex items-center justify-center">

  <!-- Main content -->
  <main class="p-10 w-full max-w-4xl bg-gray-900 shadow-lg rounded-lg">
    <h1 class="text-3xl font-semibold text-blue-400 mb-6">➕ Add New Pattern Question</h1>

    <!-- Back Button with blue color -->
    <a href="admin.php" class="text-white bg-blue-600 hover:bg-blue-700 rounded-lg px-6 py-3 mb-6 inline-block transition duration-300">← Back to Dashboard</a>

    <?php if ($success): ?>
      <div class="bg-blue-100 text-blue-800 p-4 rounded mb-6">✅ Pattern question added successfully.</div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-6">
      <label class="block text-white font-semibold">
        Upload Question Image:
        <input type="file" name="question_image" accept="image/*" required class="block mt-2 p-3 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
      </label>

      <div class="grid grid-cols-2 gap-6">
        <div>
          <label for="option_a" class="block text-white font-semibold">Option A</label>
          <input type="text" name="option_a" id="option_a" placeholder="Enter option A" required class="w-full p-3 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 bg-black text-white">
        </div>
        <div>
          <label for="option_b" class="block text-white font-semibold">Option B</label>
          <input type="text" name="option_b" id="option_b" placeholder="Enter option B" required class="w-full p-3 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 bg-black text-white">
        </div>
        <div>
          <label for="option_c" class="block text-white font-semibold">Option C</label>
          <input type="text" name="option_c" id="option_c" placeholder="Enter option C" required class="w-full p-3 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 bg-black text-white">
        </div>
        <div>
          <label for="option_d" class="block text-white font-semibold">Option D</label>
          <input type="text" name="option_d" id="option_d" placeholder="Enter option D" required class="w-full p-3 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 bg-black text-white">
        </div>
      </div>

      <div>
        <label for="correct_option" class="block text-white font-semibold">Correct Option</label>
        <input type="text" name="correct_option" id="correct_option" placeholder="Correct Option (A/B/C/D)" maxlength="1" required class="w-full p-3 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 bg-black text-white">
      </div>

      <div class="grid grid-cols-2 gap-6">
        <div>
          <label for="iq_level" class="block text-white font-semibold">IQ Level</label>
          <select name="iq_level" id="iq_level" required class="w-full p-3 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 bg-black text-white">
            <option value="">Select IQ Level</option>
            <option value="1">Level 1 (IQ ≤ 65)</option>
            <option value="2">Level 2 (IQ 66–85)</option>
            <option value="3">Level 3 (IQ ≥ 86)</option>
          </select>
        </div>
      </div>

      <!-- Add Question Button with blue color -->
      <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 transition duration-300">Add Pattern</button>
    </form>
  </main>

</body>
</html>
