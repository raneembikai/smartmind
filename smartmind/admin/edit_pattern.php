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
$query = "SELECT * FROM pattern_questions WHERE id = $id";
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
    $iq_level = $_POST['iq_level'];

    // Handle file upload if an image is provided
    $question_image = $question['question_image']; // Default to current image
    if (isset($_FILES['question_image']) && $_FILES['question_image']['error'] == 0) {
        $target_dir = "pattern_images/";
        $target_file = $target_dir . basename($_FILES["question_image"]["name"]);
        if (move_uploaded_file($_FILES["question_image"]["tmp_name"], $target_file)) {
            $question_image = basename($_FILES["question_image"]["name"]);
        }
    }

    // Update the question in the database
    $update_query = "UPDATE pattern_questions SET 
                      
                        option_a = '$option_a',
                        option_b = '$option_b',
                        option_c = '$option_c',
                        option_d = '$option_d',
                        correct_option = '$correct_option',
                        iq_level = '$iq_level',
                        question_image = '$question_image'
                    WHERE id = $id";

    if (mysqli_query($con, $update_query)) {
        header("Location: manage_pattern.php"); // Redirect after successful update
        exit();
    } else {
        echo "Update failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pattern Question</title>
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
        input[type="text"], select, input[type="file"] {
            width: 100%; padding: 10px; margin: 10px 0;
            border-radius: 5px; border: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            background: #5e2b97;
            color: white; border: none;
            border-radius: 5px; cursor: pointer;
        }
        .image-preview {
            width: 100px;
            height: auto;
            object-fit: contain;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Edit Pattern Question</h2>
    <form method="post" enctype="multipart/form-data">
      
      

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

        <label>IQ Level:</label>
        <input type="text" name="iq_level" value="<?= $question['iq_level'] ?>" required>

        <label>Question Image (optional):</label>
        <input type="file" name="question_image">

        <?php if ($question['question_image']): ?>
            <div>
                <p>Current Image:</p>
                <img src="pattern_images/<?= $question['question_image'] ?>" class="image-preview" alt="Question Image">
            </div>
        <?php endif; ?>

        <button type="submit">Update Question</button>
    </form>
</div>

</body>
</html>
