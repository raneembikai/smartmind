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

function get_last_score($user_id, $category, $con) {
    $stmt = $con->prepare("SELECT score FROM user_scores WHERE user_id = ? AND subject = ? ORDER BY timestamp DESC LIMIT 1");
    $stmt->bind_param("is", $user_id, $category);
    $stmt->execute();
    $stmt->bind_result($last_score);
    $stmt->fetch();
    $stmt->close();
    return $last_score;
}

// Age level logic
function get_age_level($dob) {
    $age = date_diff(date_create($dob), date_create('today'))->y;
    if ($age >= 7 && $age <= 13) return 1;
    elseif ($age >= 14 && $age <= 18) return 2;
    else return 3;
}

$age_level = get_age_level($dob);

// Fetch deduction questions based on age level AND current training level
$stmt = $con->prepare("SELECT * FROM deduction_questions WHERE age_level = ? AND iq_level = ? ORDER BY RAND() LIMIT 10");
$stmt->bind_param("ii", $age_level, $user_level);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
$stmt->close();

// Check if user has completed all categories (this part remains the same)
$pattern_score = get_last_score($user_id, 'Pattern', $con);
$deduction_score = get_last_score($user_id, 'Deduction', $con);
$math_score = get_last_score($user_id, 'Math', $con);

if ($pattern_score !== null && $deduction_score !== null && $math_score !== null) {
    // Update level
    $stmt = $con->prepare("UPDATE logintb SET level = level + 1 WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Deduction Training - SmartMind</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen font-sans p-6">
    <h1 class="text-4xl font-bold text-center text-green-600 mb-10">🧠 Deduction Training</h1>

    <form method="post" action="deduction_result.php" class="max-w-3xl mx-auto space-y-10">
        <?php foreach ($questions as $index => $q): ?>
            <div class="bg-green-100 p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold mb-4"><?= ($index + 1) . '. ' . htmlspecialchars($q['question_text']) ?></h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach (['A', 'B', 'C', 'D'] as $option): ?>
                        <label class="flex items-center space-x-2 bg-white p-3 rounded-lg shadow-sm hover:bg-green-50 cursor-pointer">
                            <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $option ?>" class="form-radio text-green-600">
                            <span><?= htmlspecialchars($q['option_' . strtolower($option)]) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-center">
            <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-green-700 transition">Submit</button>
        </div>
    </form>
</body>
</html>