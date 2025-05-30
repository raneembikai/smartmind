<?php
session_start();
include("inc/connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$score = 0;
$total_questions = 0;
$results_ready = false;
$feedback = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answers'])) {
    $answers = $_POST['answers'];
    $total_questions = count($answers);
    $score = 0;

    foreach ($answers as $question_id => $user_answer) {
        // Fetch question text and correct answer from DB
        $stmt = $con->prepare("SELECT question_image, correct_option FROM pattern_questions WHERE id = ?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $stmt->bind_result($question_image, $correct_option);
        $stmt->fetch();
        $stmt->close();

        $is_correct = (strcasecmp(trim($user_answer), trim($correct_option)) === 0);
        if ($is_correct) {
            $score++;
        }

        $feedback[] = [
            'question' => $question_image,
            'user_answer' => $user_answer,
            'correct_answer' => $correct_option,
            'is_correct' => $is_correct
        ];
    }

    $results_ready = true;
} else {
    $error = "No answers submitted.";
}

// Save score in user_scores table
$stmt = $con->prepare("INSERT INTO user_scores (user_id, score, subject, timestamp) VALUES (?, ?, 'Pattern', NOW())");
$stmt->bind_param("id", $user_id, $score);
$stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pattern Training Result - SmartMind</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen font-sans p-6">
    <div class="max-w-4xl mx-auto mt-10 p-8 bg-white rounded-xl shadow-md">
        <h1 class="text-3xl font-bold text-blue-600 mb-6 text-center">🧠 Pattern Training Result</h1>

        <?php if ($results_ready): ?>
            <div class="text-center mb-6">
                <p class="text-xl text-gray-700">
                    You answered <strong><?= $score ?></strong> out of <strong><?= $total_questions ?></strong> questions correctly.
                </p>

                <div class="text-2xl font-semibold mt-4 text-green-600">
                    <?php if ($score == $total_questions): ?>
                        Perfect! 🌟
                    <?php elseif ($score >= $total_questions / 2): ?>
                        Well done! 👍
                    <?php else: ?>
                        Keep practicing! 💡
                    <?php endif; ?>
                </div>

                <a href="start_training.php" class="inline-block mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                    Try Again
                </a>
            </div>

            <!-- Review Section -->
            <div class="mt-10">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">🔍 Review Your Answers</h2>
                <div class="space-y-4">
                    <?php foreach ($feedback as $index => $item): ?>
                        <div class="p-4 rounded-lg border 
                            <?= $item['is_correct'] ? 'border-green-400 bg-green-50' : 'border-red-400 bg-red-50' ?>">
                            <p class="text-gray-800 font-medium mb-1">
                                <strong>Q<?= $index + 1 ?>:</strong> 
                            </p>
                            <p>Your Answer: 
                                <span class="<?= $item['is_correct'] ? 'text-green-600' : 'text-red-600 font-semibold' ?>">
                                    <?= htmlspecialchars($item['user_answer']) ?>
                                </span>
                            </p>
                            <?php if (!$item['is_correct']): ?>
                                <p>Correct Answer: 
                                    <span class="text-blue-600 font-semibold"><?= htmlspecialchars($item['correct_answer']) ?></span>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="text-red-600 text-lg text-center"><?= isset($error) ? $error : 'Something went wrong.' ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
