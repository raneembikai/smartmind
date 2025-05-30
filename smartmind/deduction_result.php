<?php
session_start();
include("inc/connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$answers = $_POST['answers'] ?? [];

$score = 0;
$results = [];

foreach ($answers as $question_id => $user_answer) {
    $stmt = $con->prepare("SELECT question_text, correct_option, option_a, option_b, option_c, option_d FROM deduction_questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $stmt->bind_result($question_text, $correct_option, $a, $b, $c, $d);
    $stmt->fetch();
    $stmt->close();

    $is_correct = (strtoupper($user_answer) === strtoupper($correct_option));
    if ($is_correct) $score++;

    $results[] = [
        'question' => $question_text,
        'your_answer' => strtoupper($user_answer),
        'correct_answer' => strtoupper($correct_option),
        'is_correct' => $is_correct,
        'options' => ['A' => $a, 'B' => $b, 'C' => $c, 'D' => $d]
    ];
}

// Save score
$stmt = $con->prepare("INSERT INTO user_scores (user_id, score, subject, timestamp) VALUES (?, ?, 'Deduction', NOW())");
$stmt->bind_param("id", $user_id, $score);
$stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Deduction Result</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
  <div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-center text-green-700 mb-6">🧠 Deduction Training Results</h1>
    <p class="text-center text-lg mb-6">You scored <strong><?= $score ?>/10</strong></p>

    <?php foreach ($results as $index => $res): ?>
      <div class="bg-white p-5 rounded-lg shadow mb-4 border-l-4 <?= $res['is_correct'] ? 'border-green-500' : 'border-red-500' ?>">
        <p class="font-semibold mb-2"><?= ($index + 1) . '. ' . htmlspecialchars($res['question']) ?></p>
        <ul class="mb-2">
          <?php foreach ($res['options'] as $key => $value): ?>
            <li><?= $key ?>. <?= htmlspecialchars($value) ?></li>
          <?php endforeach; ?>
        </ul>
        <p class="<?= $res['is_correct'] ? 'text-green-600' : 'text-red-600' ?>">
          <?= $res['is_correct'] ? '✅ Correct!' : '❌ Incorrect. Your answer: ' . $res['your_answer'] . ', Correct: ' . $res['correct_answer'] ?>
        </p>
      </div>
    <?php endforeach; ?>

    <div class="text-center mt-6">
      <a href="start_training.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">🔁 Back to Training</a>
      <a href="index.php" class="ml-4 text-green-700 hover:underline">🏠 Home</a>
    </div>
  </div>
</body>
</html>
