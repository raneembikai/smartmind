<?php
session_start();
include 'inc/connection.php';



if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');  // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name'];  // Get admin name from session

// Get the question ID from the URL
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid request.";
    exit();
}

// Fetch the question to be edited
$query = "SELECT * FROM math_questions WHERE id = $id";
$result = mysqli_query($con, $query);
$question = mysqli_fetch_assoc($result);

if (!$question) {
    echo "Question not found!";
    exit();
}

// Handle form submission for updating the question
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $age_level = $_POST['age_level'];
    $iq_level = $_POST['iq_level'];

    // Update the question in the database
    $update_query = "UPDATE math_questions SET 
                        question_text = '$question_text',
                        option_a = '$option_a',
                        option_b = '$option_b',
                        option_c = '$option_c',
                        option_d = '$option_d',
                        correct_option = '$correct_option',
                        age_level = '$age_level',
                        iq_level = '$iq_level'
                    WHERE id = $id";

    if (mysqli_query($con, $update_query)) {
        header("Location: manage_math.php"); // Redirect after successful update
        exit();
    } else {
        echo "Update failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Math Question</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; }
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
    <h2>Edit Math Question</h2>
    <form method="post">
        <label>Question:</label>
        <input type="text" name="question_text" value="<?= htmlspecialchars($question['question_text']) ?>" required>

        <label>Option A:</label>
        <input type="text" name="option_a" value="<?= htmlspecialchars($question['option_a']) ?>" required>

        <label>Option B:</label>
        <input type="text" name="option_b" value="<?= htmlspecialchars($question['option_b']) ?>" required>

        <label>Option C:</label>
        <input type="text" name="option_c" value="<?= htmlspecialchars($question['option_c']) ?>" required>

        <label>Option D:</label>
        <input type="text" name="option_d" value="<?= htmlspecialchars($question['option_d']) ?>" required>

        <label>Correct Answer:</label>
        <select name="correct_option" required>
            <option value="A" <?= $question['correct_option'] == 'A' ? 'selected' : '' ?>>A</option>
            <option value="B" <?= $question['correct_option'] == 'B' ? 'selected' : '' ?>>B</option>
            <option value="C" <?= $question['correct_option'] == 'C' ? 'selected' : '' ?>>C</option>
            <option value="D" <?= $question['correct_option'] == 'D' ? 'selected' : '' ?>>D</option>
        </select>

        <label>Age Level (1, 2, 3):</label>
        <input type="text" name="age_level" value="<?= $question['age_level'] ?>" required>

        <label>IQ Level:</label>
        <input type="text" name="iq_level" value="<?= $question['iq_level'] ?>" required>

        <button type="submit">Update Question</button>
    </form>
</div>

</body>
</html>
