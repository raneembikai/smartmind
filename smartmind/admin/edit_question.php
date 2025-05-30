<?php
session_start();
include 'inc/connection.php';



if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');  // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name'];  // Get admin name from session



$id = $_GET['id'] ?? null;

if ($id) {
    // Get current question
    $query = "SELECT * FROM iqquestions WHERE id = $id";
    $result = mysqli_query($con, $query);
    $question = mysqli_fetch_assoc($result);

    if (!$question) {
        echo "Question not found!";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $q = $_POST['question'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];
    $correct = $_POST['correct_answer'];
    $level = $_POST['level'];

    $update = "UPDATE iqquestions SET 
        question='$q', option_a='$a', option_b='$b', option_c='$c', option_d='$d',
        correct_answer='$correct', level='$level' 
        WHERE id=$id";

    if (mysqli_query($con, $update)) {
        header("Location: manage_iqquestions.php");
        exit();
    } else {
        echo "Update failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Question</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; }
        .form-box {
            width: 60%;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #5e2b97; }
        input[type="text"], select {
            width: 100%; padding: 10px; margin: 10px 0;
            border-radius: 5px; border: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            background: #5e2b97;
            color: white; border: none;
            border-radius: 5px; cursor: pointer;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Edit IQ Question</h2>
    <form method="post">
        <label>Question:</label>
        <input type="text" name="question" value="<?= htmlspecialchars($question['question']) ?>" required>

        <label>Option A:</label>
        <input type="text" name="option_a" value="<?= htmlspecialchars($question['option_a']) ?>" required>

        <label>Option B:</label>
        <input type="text" name="option_b" value="<?= htmlspecialchars($question['option_b']) ?>" required>

        <label>Option C:</label>
        <input type="text" name="option_c" value="<?= htmlspecialchars($question['option_c']) ?>" required>

        <label>Option D:</label>
        <input type="text" name="option_d" value="<?= htmlspecialchars($question['option_d']) ?>" required>

        <label>Correct Answer:</label>
        <select name="correct_answer" required>
            <option value="A" <?= $question['correct_answer'] == 'A' ? 'selected' : '' ?>>A</option>
            <option value="B" <?= $question['correct_answer'] == 'B' ? 'selected' : '' ?>>B</option>
            <option value="C" <?= $question['correct_answer'] == 'C' ? 'selected' : '' ?>>C</option>
            <option value="D" <?= $question['correct_answer'] == 'D' ? 'selected' : '' ?>>D</option>
        </select>

        <label>Level (1, 2, 3):</label>
        <input type="text" name="level" value="<?= $question['level'] ?>" required>

        <button type="submit">Update Question</button>
    </form>
</div>

</body>
</html>
