<?php
session_start();

// Check if score and wrong answers are passed via URL
if (!isset($_GET['score']) || !isset($_GET['wrong_answers'])) {
    header("Location: math_training.php");
    exit();
}

// Sanitize and validate score
$score = filter_var($_GET['score'], FILTER_VALIDATE_INT);
if ($score === false) {
    header("Location: math_training.php");
    exit();
}

// Unserialize wrong answers safely
$wrong_answers = @unserialize(urldecode($_GET['wrong_answers']));
if (!is_array($wrong_answers)) {
    $wrong_answers = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Math Result Summary</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen font-sans p-6">
  <h1 class="text-4xl font-bold text-center text-blue-600 mb-10">Math Training Results</h1>

  <div class="max-w-3xl mx-auto bg-blue-100 p-6 rounded-xl shadow-md mb-10">
    <h2 class="text-2xl font-semibold text-blue-600">Your Score: <?= htmlspecialchars($score) ?> / 10</h2>

    <h3 class="text-lg mt-4">Wrong Answers:</h3>
    <?php if (empty($wrong_answers)): ?>
      <p class="text-green-600 font-semibold mt-2">🎉 Congratulations! You got all the answers correct!</p>
    <?php else: ?>
      <ul class="space-y-6 mt-4">
        <?php foreach ($wrong_answers as $answer): ?>
          <li class="bg-white p-4 rounded-lg shadow">
            <p><strong>Question:</strong> <?= htmlspecialchars($answer['question']) ?></p>
            <p><strong>Your Answer:</strong> <?= htmlspecialchars($answer['user_answer']) ?></p>
            <p><strong>Correct Answer:</strong> <?= htmlspecialchars($answer['correct_answer']) ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

  <div class="text-center space-x-4">
    <a href="math_training.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">Try Again</a>
    <a href="start_training.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">Choose Category</a>
  </div>
</body>
</html>