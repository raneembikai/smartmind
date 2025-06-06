<?php
session_start();
include("inc/connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's dob and current level
$stmt = $con->prepare("SELECT dob, level FROM logintb WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($dob, $user_level);
$stmt->fetch();
$stmt->close();

// Convert age to level
function get_age_level($dob) {
    $age = date_diff(date_create($dob), date_create('today'))->y;
    if ($age >= 7 && $age <= 13) return 1;
    elseif ($age >= 14 && $age <= 18) return 2;
    else return 3;
}

$age_level = get_age_level($dob);

// Fetch 10 questions for the user's age level AND current training level
$stmt = $con->prepare("SELECT * FROM math_questions WHERE age_level = ? AND iq_level = ? ORDER BY RAND() LIMIT 10");
$stmt->bind_param("ii", $age_level, $user_level);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $wrong_answers = [];
    $score = 0;

    foreach ($questions as $q) {
        $user_answer = $_POST['answers'][$q['id']] ?? '';
        if ($user_answer === $q['correct_option']) {
            $score++;
        } else {
            $wrong_answers[] = [
                'question' => $q['question_text'],
                'user_answer' => $q['option_' . strtolower($user_answer)] ?? '',
                'correct_answer' => $q['option_' . strtolower($q['correct_option'])],
            ];
        }
    }

    // Insert score into database
    $stmt = $con->prepare("INSERT INTO user_scores (user_id, score, subject, timestamp) VALUES (?, ?, 'Math', NOW())");
    $stmt->bind_param("ii", $user_id, $score);
    $stmt->execute();
    $stmt->close();

    // Redirect with serialized wrong answers
    header("Location: math_result_summary.php?score=$score&wrong_answers=" . urlencode(serialize($wrong_answers)));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Math Training - SmartMind</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen font-sans p-6">
    <h1 class="text-4xl font-bold text-center text-blue-600 mb-10">📐 Math Training</h1>

    <form method="post" action="" class="max-w-3xl mx-auto space-y-10">
        <?php foreach ($questions as $index => $q): ?>
            <div class="bg-blue-100 p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold mb-4"><?= ($index + 1) . '. ' . htmlspecialchars($q['question_text']) ?></h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach (['A', 'B', 'C', 'D'] as $option): ?>
                        <label class="flex items-center space-x-2 bg-white p-3 rounded-lg shadow-sm hover:bg-blue-50 cursor-pointer">
                            <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $option ?>" class="form-radio text-blue-600" required>
                            <span><?= htmlspecialchars($q['option_' . strtolower($option)]) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-center">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">Submit</button>
        </div>
    </form>
</body>
</html>