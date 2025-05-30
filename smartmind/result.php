<?php
session_start();
include("inc/connection.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$stmt = $con->prepare("SELECT test_taken, level FROM logintb WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($test_taken, $current_level);
$stmt->fetch();
$stmt->close();

$score = 0;
$total = 20;
$percentage = 0;
$message = "";
$wrong_answers = [];

if ($test_taken == 1) {
   
    $stmt = $con->prepare("
        SELECT COUNT(*)
        FROM user_answers ua
        JOIN iqquestions q ON ua.question_id = q.id
        WHERE ua.user_id = ? AND ua.selected_option = q.correct_answer
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($score);
    $stmt->fetch();
    $stmt->close();

    $percentage = ($score / $total) * 100;
    $iq_score = round($percentage);

    
    $initial_level = 0;
    if ($iq_score >= 85) {
        $initial_level = 3;
    } elseif ($iq_score >= 65) {
        $initial_level = 2;
    } elseif ($iq_score >= 50) {
        $initial_level = 1;
    } else {
        $initial_level = 1;
    }

    
    if ($initial_level > $current_level) {
        $stmt = $con->prepare("UPDATE logintb SET level = ? WHERE id = ?");
        $stmt->bind_param("ii", $initial_level, $user_id);
        $stmt->execute();
        $stmt->close();
        $current_level = $initial_level;
    }

    
    $stmt = $con->prepare("
        SELECT q.question, ua.selected_option, q.correct_answer, q.option_a, q.option_b, q.option_c, q.option_d
        FROM user_answers ua
        JOIN iqquestions q ON ua.question_id = q.id
        WHERE ua.user_id = ? AND ua.selected_option != q.correct_answer
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($question_text, $selected_option, $correct_answer, $optA, $optB, $optC, $optD);

    while ($stmt->fetch()) {
        $options_map = [
            'A' => $optA,
            'B' => $optB,
            'C' => $optC,
            'D' => $optD,
        ];

        $wrong_answers[] = [
            'question' => $question_text,
            'your_answer' => $options_map[$selected_option] ?? $selected_option,
            'correct_answer' => $options_map[$correct_answer] ?? $correct_answer
        ];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Your IQ Test Result - SmartMind</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-100 to-pink-200 min-h-screen font-sans">
    <div class="flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-2xl rounded-2xl p-10 w-full max-w-xl text-center">
            <h1 class="text-4xl font-bold text-green-700 mb-4">IQ Test Completed</h1>
            <p class="text-lg text-gray-700 mb-6">You answered <strong class="text-blue-600"><?= $score ?></strong> out of <strong class="text-blue-600"><?= $total ?></strong> questions correctly.</p>
            <p class="text-2xl font-semibold text-pink-600 mb-4">Your Score: <?= round($percentage) ?>%</p>
            <p class="text-lg text-gray-800 mb-8 italic"><?= $message ?></p>
            <a href="profile.php" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-300">Back to Home</a>
            <a href="logic_training.php" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-300 mt-4">
                Train Your Logic Now?
            </a>
        </div>

        <?php if (!empty($wrong_answers)): ?>
        <div class="mt-10 w-full max-w-4xl bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-red-600 mb-4 text-center">Questions You Got Wrong</h2>
            <?php foreach ($wrong_answers as $item): ?>
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 rounded">
                    <p class="font-semibold text-gray-800">Question:</p>
                    <p class="text-gray-700 mb-2"><?= htmlspecialchars($item['question']) ?></p>
                    <p><span class="font-semibold text-gray-700">Your Answer:</span> <span class="text-red-600"><?= htmlspecialchars($item['your_answer']) ?></span></p>
                    <p><span class="font-semibold text-gray-700">Correct Answer:</span> <span class="text-green-600"><?= htmlspecialchars($item['correct_answer']) ?></span></p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>