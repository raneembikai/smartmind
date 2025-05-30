<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartMind - Memory Card Match Game</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #e3f2fd; /* Soft blue background */
            margin: 0;
            padding: 30px;
        }
        h1 {
            text-align: center;
            color: #1565c0; /* Blue title */
            margin-bottom: 30px;
        }
        .game-board {
            display: grid;
            grid-template-columns: repeat(4, 120px);
            gap: 10px;
            justify-content: center;
            margin: 0 auto;
            max-width: 500px;
        }
        .card {
            width: 100px;
            height: 100px;
            background-color: #bbdefb; /* Light blue card back */
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            cursor: pointer;
            font-size: 24px;
            color: transparent;
            box-shadow: 0 0 10px rgba(21, 101, 192, 0.3);
            transition: transform 0.3s;
        }
        .card.flipped {
            background-color: #ffffff;
            color: #0d47a1;
        }
        .card.matched {
            background-color: #4fc3f7;
            color: white;
            cursor: default;
        }
        .game-over {
            text-align: center;
            margin-top: 20px;
            font-size: 24px;
            color: #1565c0;
        }
        .back-btn {
            display: block;
            text-align: center;
            margin-top: 40px;
        }
        .back-btn a {
            text-decoration: none;
            background-color: #1565c0;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        .back-btn a:hover {
            background-color: #0d47a1;
        }
    </style>
</head>
<body>

    <h1>🎮 Memory Card Match Game</h1>
    <div class="game-board" id="game-board"></div>

    <div class="game-over" id="game-over" style="display: none;">
        <p>🎉 Congratulations! You've matched all cards!</p>
        <button onclick="resetGame()">Play Again</button>
    </div>

    <div class="back-btn">
        <a href="games.php">← Back to Dashboard</a>
    </div>

    <script>
        const cards = [
            'A', 'A', 'B', 'B', 'C', 'C', 'D', 'D', 'E', 'E', 'F', 'F', 'G', 'G', 'H', 'H'
        ];
        let flippedCards = [];
        let matchedCards = 0;
        let cardElements = [];
        const gameBoard = document.getElementById('game-board');
        const gameOverMessage = document.getElementById('game-over');

        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
        }

        function createCardElements() {
            cards.forEach((card, index) => {
                const cardElement = document.createElement('div');
                cardElement.classList.add('card');
                cardElement.dataset.index = index;
                cardElement.addEventListener('click', flipCard);
                gameBoard.appendChild(cardElement);
                cardElements.push(cardElement);
            });
        }

        function flipCard(event) {
            const card = event.target;
            const index = card.dataset.index;

            if (card.classList.contains('flipped') || card.classList.contains('matched')) return;

            card.classList.add('flipped');
            card.textContent = cards[index];
            flippedCards.push(card);

            if (flippedCards.length === 2) {
                checkMatch();
            }
        }

        function checkMatch() {
            if (flippedCards[0].textContent === flippedCards[1].textContent) {
                flippedCards[0].classList.add('matched');
                flippedCards[1].classList.add('matched');
                matchedCards += 2;
                flippedCards = [];

                if (matchedCards === cards.length) {
                    gameOver();
                }
            } else {
                setTimeout(() => {
                    flippedCards[0].classList.remove('flipped');
                    flippedCards[1].classList.remove('flipped');
                    flippedCards[0].textContent = '';
                    flippedCards[1].textContent = '';
                    flippedCards = [];
                }, 1000);
            }
        }

        function gameOver() {
            gameOverMessage.style.display = 'block';
        }

        function resetGame() {
            matchedCards = 0;
            flippedCards = [];
            gameOverMessage.style.display = 'none';
            cards.sort(() => Math.random() - 0.5);
            cardElements.forEach(card => {
                card.classList.remove('flipped', 'matched');
                card.textContent = '';
            });
        }

        // Initialize game
        shuffleArray(cards);
        createCardElements();
    </script>

</body>
</html>
