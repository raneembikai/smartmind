<?php

$response = file_get_contents("https://opentdb.com/api.php?amount=25&category=18&difficulty=medium");
$data = json_decode($response, true);

$questions = $data['results'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logic Trivia Challenge</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #a8edea, #fed6e3);
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
<a href="profile.php" class="absolute top-4 left-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl focus:outline-none focus:shadow-outline">
        Go to Home Page
    </a>
  <div class="bg-white p-6 rounded-2xl shadow-xl max-w-2xl w-full" id="quiz-container">
    <h2 class="text-2xl font-bold mb-4 text-center" id="question-title">Loading...</h2>
    <div id="options" class="space-y-2"></div>
    <button id="next-btn" class="mt-6 w-full bg-purple-500 text-white font-bold py-2 px-4 rounded-xl hover:bg-purple-600 hidden">Next</button>
    <div id="result" class="text-center text-xl font-bold mt-6 hidden"></div>
  </div>

  <script>
    const questions = <?php echo json_encode($questions); ?>;
    let currentQuestion = 0;
    let score = 0;

    const questionTitle = document.getElementById('question-title');
    const optionsContainer = document.getElementById('options');
    const nextBtn = document.getElementById('next-btn');
    const resultContainer = document.getElementById('result');

    function shuffle(array) {
      return array.sort(() => Math.random() - 0.5);
    }

    function decodeHTMLEntities(text) {
      const txt = document.createElement('textarea');
      txt.innerHTML = text;
      return txt.value;
    }

    function showQuestion() {
      const q = questions[currentQuestion];
      questionTitle.innerHTML = `Q${currentQuestion + 1}: ${decodeHTMLEntities(q.question)}`;
      const options = shuffle([q.correct_answer, ...q.incorrect_answers]);
      optionsContainer.innerHTML = '';
      options.forEach(opt => {
        const btn = document.createElement('button');
        btn.className = "w-full bg-mint-300 hover:bg-pink-300 transition p-2 rounded-xl border";
        btn.innerText = decodeHTMLEntities(opt);
        btn.onclick = () => {
          if (opt === q.correct_answer) {
            score++;
            btn.classList.add("bg-green-200");
          } else {
            btn.classList.add("bg-red-200");
          }
          [...optionsContainer.children].forEach(b => b.disabled = true);
          nextBtn.classList.remove("hidden");
        };
        optionsContainer.appendChild(btn);
      });
    }

    nextBtn.onclick = () => {
      currentQuestion++;
      nextBtn.classList.add("hidden");
      if (currentQuestion < questions.length) {
        showQuestion();
      } else {
        showResult();
      }
    };

    function showResult() {
      questionTitle.innerHTML = "🎉 Quiz Completed!";
      optionsContainer.innerHTML = "";
      resultContainer.innerHTML = `Your Score: ${score} / ${questions.length}`;
      resultContainer.classList.remove("hidden");
    }

    showQuestion();
  </script>
</body>
</html>
