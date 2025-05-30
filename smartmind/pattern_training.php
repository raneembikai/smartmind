<?php
session_start();
include("inc/connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

function get_last_score($user_id, $category, $con) {
    $stmt = $con->prepare("SELECT score FROM user_scores WHERE user_id = ? AND subject = ? ORDER BY timestamp DESC LIMIT 1");
    $stmt->bind_param("is", $user_id, $category);
    $stmt->execute();
    $stmt->bind_result($last_score);
    $stmt->fetch();
    $stmt->close();
    return $last_score;
}

// Convert age to level
function get_age_level($dob) {
    $age = date_diff(date_create($dob), date_create('today'))->y;
    if ($age >= 7 && $age <= 13) return 1;
    elseif ($age >= 14 && $age <= 18) return 2;
    else return 3;
}

// ✅ Fetch user's dob and current level
$stmt = $con->prepare("SELECT dob, level FROM logintb WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($dob, $user_level);
$stmt->fetch();
$stmt->close();

// ✅ Determine age level
$age_level = get_age_level($dob);

// ✅ Fetch 10 pattern questions for this user level AND age level
$stmt = $con->prepare("SELECT * FROM pattern_questions WHERE iq_level = ? ORDER BY RAND() LIMIT 10");
$stmt->bind_param("i", $user_level);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
$stmt->close();

// Handle form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['answers'])) {
        echo "No answers were submitted.";
    }
}

$training_done = isset($_GET['done']) && $_GET['done'] == 1;
$last_score = isset($_GET['score']) ? intval($_GET['score']) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pattern Training - SmartMind</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen font-sans p-6">
    <h1 class="text-4xl font-bold text-center text-blue-600 mb-10">🧩 Pattern Training</h1>

    <?php if ($training_done): ?>
        <div class="text-center text-xl font-semibold text-green-600 mb-6">
            <p>You scored <strong><?= $last_score ?></strong> out of <?= count($questions) ?> questions. Great job!</p>
        </div>
    <?php endif; ?>

    <form method="post" action="pattern_result.php" class="max-w-3xl mx-auto space-y-10">
        <?php foreach ($questions as $index => $q): ?>
            <div class="bg-blue-100 p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold mb-4"><?= ($index + 1) . '. ' ?>Complete the figure</h2>

                <?php if (!empty($q['question_image'])): ?>
                    <div class="mb-4">
                        <img src="admin/pattern_images/<?= htmlspecialchars($q['question_image']) ?>" alt="Pattern Image" class="w-full h-full object-cover rounded-lg shadow-sm">
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach (['A', 'B', 'C', 'D'] as $option): ?>
                        <label class="flex items-center space-x-2 bg-white p-3 rounded-lg shadow-sm hover:bg-blue-50 cursor-pointer">
                            <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $option ?>" class="form-radio text-blue-600">
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