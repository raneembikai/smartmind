<?php
session_start();
include 'inc/connection.php';



if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');  // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name'];  // Get admin name from session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct = $_POST['correct_answer'];
    $level = $_POST['level'];

    $query = "INSERT INTO iqquestions (question, option_a, option_b, option_c, option_d, correct_answer, level) 
              VALUES ('$question', '$option_a', '$option_b', '$option_c', '$option_d', '$correct', '$level')";

    if (mysqli_query($con, $query)) {
        header("Location: manage_iqquestions.php");
        exit();
    } else {
        echo "Error adding question.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New IQ Question</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0a0a0a;
            color: #e0e0e0;
        }
        .form-box {
            width: 60%;
            margin: 40px auto;
            background: #111;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 170, 255, 0.3);
        }
        h2 {
            text-align: center;
            color: #00aaff;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #00ccff;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #333;
            background-color: #222;
            color: #fff;
        }
        input[type="text"]::placeholder {
            color: #aaa;
        }
        select {
            background-color: #222;
            color: #fff;
        }
        button {
            margin-top: 25px;
            background-color: #00aaff;
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #0088cc;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Add New IQ Question</h2>
    <form method="post">
        <label>Question</label>
        <input type="text" name="question" required placeholder="Enter the question">

        <label>Option A</label>
        <input type="text" name="option_a" required placeholder="Enter option A">

        <label>Option B</label>
        <input type="text" name="option_b" required placeholder="Enter option B">

        <label>Option C</label>
        <input type="text" name="option_c" required placeholder="Enter option C">

        <label>Option D</label>
        <input type="text" name="option_d" required placeholder="Enter option D">

        <label>Correct Answer</label>
        <select name="correct_answer" required>
            <option value="">-- Select --</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select>

        <label>Level</label>
        <select name="level" required>
            <option value="">-- Select Level --</option>
            <option value="1">1 (Age 7–13)</option>
            <option value="2">2 (Age 14–18)</option>
            <option value="3">3 (Age 19+)</option>
        </select>

        <button type="submit">Add Question</button>
    </form>
</div>

</body>
</html>
