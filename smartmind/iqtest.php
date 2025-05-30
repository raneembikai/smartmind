<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQ Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(135deg, #dbeafe, #e0f2fe);
        }

        .background-design {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 30%, #cfe8ff 0%, transparent 70%),
                        radial-gradient(circle at 80% 70%, #a5d8ff 0%, transparent 60%);
            z-index: -1;
        }

        #question-container button {
            background: #e0f2fe;
            transition: all 0.2s ease-in-out;
        }

        #question-container button:hover {
            background: #bfdbfe;
            transform: translateY(-2px);
        }

        #start-button {
            background-color: #3b82f6;
        }

        #start-button:hover {
            background-color: #2563eb;
        }

        #timer {
            background-color: #e0f2fe;
            color: #1e3a8a;
            font-weight: bold;
        }
    </style>
    <script>
        let timerInterval;
        let seconds = 0;
        let minutes = 0;
        let hours = 0;
        let currentQuestionIndex = 0;
        let questions = [];

        function startTimer() {
            timerInterval = setInterval(() => {
                seconds++;
                if (seconds === 60) {
                    seconds = 0;
                    minutes++;
                }
                if (minutes === 60) {
                    minutes = 0;
                    hours++;
                }
                document.getElementById('timer').innerText = 
                    (hours < 10 ? '0' + hours : hours) + ':' + 
                    (minutes < 10 ? '0' + minutes : minutes) + ':' + 
                    (seconds < 10 ? '0' + seconds : seconds);
            }, 1000);
        }

        function handleOptionClick(selectedOption) {
            if (!timerInterval) {
                startTimer();
            }
            const question = questions[currentQuestionIndex];
            if (selectedOption === question.answer) {
                console.log("Correct answer!");
            } else {
                console.log("Incorrect answer. The correct answer was: " + question.answer);
            }
            nextQuestion();
        }

        function nextQuestion() {
            if (currentQuestionIndex < questions.length - 1) {
                currentQuestionIndex++;
                displayQuestion();
            } else {
                alert("Test completed!");
                // Optionally redirect or show score
            }
        }

        function displayQuestion() {
            const questionContainer = document.getElementById('question-container');
            const question = questions[currentQuestionIndex];
            questionContainer.innerHTML = `
                <h3 class="text-lg text-blue-700 font-semibold mb-2">Question ${currentQuestionIndex + 1}/${questions.length}</h3>
                <p class="text-blue-800 text-center mb-4">${question.question}</p>
                <div class="grid grid-cols-2 gap-4">
                    ${JSON.parse(question.options).map(option => `
                        <button class="py-2 px-4 rounded-lg text-blue-900 font-medium shadow-md" onclick="handleOptionClick('${option}')">${option}</button>
                    `).join('')}
                </div>
            `;
        }

        function startTest() {
            fetchQuestions();
        }

        function fetchQuestions() {
            fetch(`http://localhost:3000/questions/random?count=25`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    questions = data;
                    currentQuestionIndex = 0;
                    displayQuestion();
                })
                .catch(error => {
                    console.error('Error fetching questions:', error);
                    alert('Failed to load questions. Please try again later.');
                });
        }
    </script>
</head>
<body class="relative">
    <div class="background-design"></div>
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-3xl relative">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-blue-700">IQ TEST</h1>
                <h2 class="text-lg text-blue-600 mt-1">Check Your Level</h2>
                <button id="start-button" class="text-white py-2 px-6 rounded-full mt-4 shadow-md hover:shadow-lg transition-all" onclick="window.location.href='iqquestions.php'">Start Test</button>
            </div>
            <div class="flex justify-center my-4">
                <div class="rounded-full px-5 py-2" id="timer">00:00:00</div>
            </div>
            <div id="question-container" class="p-6 rounded-xl bg-blue-50 shadow-inner transition-all duration-300 ease-in-out"></div>
        </div>
    </div>
</body>
</html>
