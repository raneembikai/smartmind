<?php
session_start();
include("inc/connection.php");

$user_id = $_SESSION['user_id'] ?? null;
$level = 1; // Default
$iq_score = $_SESSION['iq_score'] ?? null;



if (!$user_id) {
    header("Location: login.php");

    exit();
}



// Get user data
$stmt = $con->prepare("SELECT dob, test_taken  FROM logintb WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($dob, $test_taken );
$stmt->fetch();
$stmt->close();

// If test already taken, redirect
if ($test_taken == 1) {
    header("Location: result.php");
    exit();
}

// Calculate age
$today = new DateTime();
$birthdate = new DateTime($dob);
$age = $today->diff($birthdate)->y;

// Determine level
if ($age >= 7 && $age <= 13) {
    $level = 1;
} elseif ($age >= 14 && $age <= 18) {
    $level = 2;
} else {
    $level = 3;
}

// Fetch 20 questions for this level
$query = "SELECT * FROM iqquestions WHERE level = $level ORDER BY RAND() LIMIT 20";
$result = mysqli_query($con, $query);

$questions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $questions[] = [
        'id' => $row['id'],
        'question' => $row['question'],
        'options' => [
            $row['option_a'],
            $row['option_b'],
            $row['option_c'],
            $row['option_d']
        ],
        'answer' => $row['correct_answer']
    ];
}

$jsonQuestions = json_encode($questions);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IQ Test - SmartMind</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-200 min-h-screen flex items-center justify-center font-sans">
    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-xl">
        <h1 class="text-3xl font-bold text-blue-700 mb-2 text-center">Let's check your IQ</h1>
        <p class="text-gray-700 text-center mb-4">Choose the right answers</p>

        <!-- Progress bar -->
        <div class="w-full bg-gray-300 rounded-full h-2.5 mb-6">
            <div id="progress-bar" class="bg-blue-500 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
        </div>

        <!-- Question Text -->
        <p class="text-gray-800 font-semibold text-lg mb-6 text-center" id="question-text">Loading question...</p>

        <!-- Answer Options -->
        <div class="grid grid-cols-2 gap-4 text-left mb-6">
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="radio" name="answer" value="A" onclick="answerSelected('A')" class="form-radio text-blue-600 h-5 w-5">
                <span id="option-a">Option A</span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="radio" name="answer" value="B" onclick="answerSelected('B')" class="form-radio text-blue-600 h-5 w-5">
                <span id="option-b">Option B</span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="radio" name="answer" value="C" onclick="answerSelected('C')" class="form-radio text-blue-600 h-5 w-5">
                <span id="option-c">Option C</span>
            </label>
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="radio" name="answer" value="D" onclick="answerSelected('D')" class="form-radio text-blue-600 h-5 w-5">
                <span id="option-d">Option D</span>
            </label>
        </div>

        <!-- Next Button -->
        <div class="text-center">
            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow" onclick="nextQuestion()">Next</button>
        </div>
    </div>

    <script>
        const questions = <?= $jsonQuestions ?>;
        const userId = <?= $user_id ?>;
        let currentQuestionIndex = 0;
        let score = 0;
        let selectedAnswer = null;

        function loadQuestion() {
            const q = questions[currentQuestionIndex];
            document.getElementById("question-text").textContent = q.question;
            document.getElementById("option-a").textContent = q.options[0];
            document.getElementById("option-b").textContent = q.options[1];
            document.getElementById("option-c").textContent = q.options[2];
            document.getElementById("option-d").textContent = q.options[3];
            document.querySelectorAll('input[name="answer"]').forEach(input => input.checked = false);
            selectedAnswer = null;

            document.getElementById("progress-bar").style.width = `${(currentQuestionIndex / questions.length) * 100}%`;
        }

        function answerSelected(option) {
            selectedAnswer = option;
        }

        function saveUserAnswer(userId, questionId, selectedOption) {
            fetch('save_answer.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId, question_id: questionId, selected_option: selectedOption })
            })
            .then(res => res.text())
            .then(data => console.log("Saved:", data))
            .catch(err => console.error("Error saving answer:", err));
        }

        function nextQuestion() {
            if (!selectedAnswer) {
                alert("Please select an answer.");
                return;
            }

            const currentQuestion = questions[currentQuestionIndex];
            saveUserAnswer(userId, currentQuestion.id, selectedAnswer);

            if (selectedAnswer === currentQuestion.answer) {
                score++;
            }

            currentQuestionIndex++;
            if (currentQuestionIndex < questions.length) {
                loadQuestion();
            } else {
                fetch('finish_test.php', { method: 'POST' })
                .then(() => {
        window.location.href = `result.php`;
    });

            }
        }
    

        window.onload = loadQuestion;
    </script>
</body>
</html>
