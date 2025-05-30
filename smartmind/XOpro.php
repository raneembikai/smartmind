<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tic-Tac-Toe</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #cbe4f9, #e0f7fa);
      color: #0d47a1;
      text-align: center;
      padding: 20px;
    }

    h1 {
      font-size: 3rem;
      margin-bottom: 10px;
      color: #01579b;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(3, 100px);
      grid-gap: 15px;
      justify-content: center;
      margin: 30px auto;
    }

    .cell {
      width: 100px;
      height: 100px;
      background-color: #bbdefb;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      color: #0d47a1;
      border-radius: 15px;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      transition: background 0.3s;
    }

    .cell:hover {
      background-color: #90caf9;
    }

    .cell.winning {
      animation: glow 1s infinite alternate;
      background-color: #64b5f6 !important;
    }

    @keyframes glow {
      from { box-shadow: 0 0 5px #fff; }
      to { box-shadow: 0 0 20px #2196f3; }
    }

    .buttons {
      margin-top: 20px;
    }

    .button {
      padding: 12px 24px;
      margin: 10px;
      font-size: 18px;
      background-color: #42a5f5;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .button:hover {
      background-color: #1e88e5;
    }

    .message {
      font-size: 24px;
      margin-top: 20px;
      color: #0d47a1;
    }

    .modal {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.6);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 10;
    }

    .modal-content {
      background: #ffffff;
      padding: 40px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
      animation: popup 0.4s ease;
    }

    .modal-content h2 {
      margin-bottom: 20px;
      color: #0d47a1;
    }

    .modal-content .mode-button {
      background-color: #42a5f5;
      color: white;
      padding: 12px 24px;
      margin: 10px;
      font-size: 18px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .modal-content .mode-button:hover {
      background-color: #1e88e5;
    }

    @keyframes popup {
      from { transform: scale(0.8); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    @media (max-width: 500px) {
      .grid {
        grid-template-columns: repeat(3, 80px);
      }

      .cell {
        width: 80px;
        height: 80px;
        font-size: 2rem;
      }

      .button {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>

  <h1>Tic-Tac-Toe ❌⭕</h1>

  <div class="grid" id="gameGrid"></div>

  <div class="message" id="gameMessage"></div>

  <div class="buttons">
    <button class="button" id="restartBtn">🔁 Restart Game</button>
    <button class="button" onclick="window.location.href='gamesPro.php'">🎮 Back to Games</button>
  </div>

 
  <div class="modal" id="modeModal">
    <div class="modal-content">
      <h2>Select Game Mode</h2>
      <button class="mode-button" onclick="selectMode('single')">🎯 Single Player</button>
      <button class="mode-button" onclick="selectMode('multi')">🤝 Multi Player</button>
    </div>
  </div>

  
  <audio id="clickSound" src="https://www.fesliyanstudios.com/play-mp3/387"></audio>
  <audio id="winSound" src="https://www.fesliyanstudios.com/play-mp3/4385"></audio>
  <audio id="drawSound" src="https://www.fesliyanstudios.com/play-mp3/6774"></audio>

  <script>
    const grid = document.getElementById('gameGrid');
    const gameMessage = document.getElementById('gameMessage');
    const restartBtn = document.getElementById('restartBtn');
    const clickSound = document.getElementById('clickSound');
    const winSound = document.getElementById('winSound');
    const drawSound = document.getElementById('drawSound');
    const modeModal = document.getElementById('modeModal');

    let currentPlayer = 'X';
    let board = Array(9).fill(null);
    let gameOver = false;
    let gameMode = '';

   
    for (let i = 0; i < 9; i++) {
      const cell = document.createElement('div');
      cell.classList.add('cell');
      cell.dataset.index = i;
      cell.addEventListener('click', handleCellClick);
      grid.appendChild(cell);
    }

    function selectMode(mode) {
      gameMode = mode;
      modeModal.style.display = 'none';
    }

    function handleCellClick(e) {
      const index = e.target.dataset.index;

      if (board[index] || gameOver) return;

      clickSound.play();
      board[index] = currentPlayer;
      e.target.textContent = currentPlayer;

      const winningCells = checkWinner();
      if (winningCells) {
        gameOver = true;
        gameMessage.textContent = `${currentPlayer} wins! 🎉`;
        winSound.play();
        highlightWinningCells(winningCells);
        return;
      }

      if (board.every(cell => cell)) {
        gameOver = true;
        gameMessage.textContent = "It's a draw! 🤝";
        drawSound.play();
        return;
      }

      if (gameMode === 'single' && currentPlayer === 'X') {
        currentPlayer = 'O';
        setTimeout(computerMove, 500);
      } else {
        currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
      }
    }

    function computerMove() {
      if (gameOver) return;

      let emptyIndices = board.map((val, i) => val === null ? i : null).filter(i => i !== null);
      let randIndex = emptyIndices[Math.floor(Math.random() * emptyIndices.length)];

      board[randIndex] = 'O';
      document.querySelectorAll('.cell')[randIndex].textContent = 'O';
      clickSound.play();

      const winningCells = checkWinner();
      if (winningCells) {
        gameOver = true;
        gameMessage.textContent = "O wins! 🎉";
        winSound.play();
        highlightWinningCells(winningCells);
        return;
      }

      if (board.every(cell => cell)) {
        gameOver = true;
        gameMessage.textContent = "It's a draw! 🤝";
        drawSound.play();
        return;
      }

      currentPlayer = 'X';
    }

    function checkWinner() {
      const winPatterns = [
        [0,1,2],[3,4,5],[6,7,8],
        [0,3,6],[1,4,7],[2,5,8],
        [0,4,8],[2,4,6]
      ];

      for (let pattern of winPatterns) {
        const [a,b,c] = pattern;
        if (board[a] && board[a] === board[b] && board[a] === board[c]) {
          return pattern;
        }
      }
      return null;
    }

    function highlightWinningCells(indices) {
      indices.forEach(i => {
        document.querySelectorAll('.cell')[i].classList.add('winning');
      });
    }

    restartBtn.addEventListener('click', () => {
      board = Array(9).fill(null);
      currentPlayer = 'X';
      gameOver = false;
      gameMessage.textContent = '';
      document.querySelectorAll('.cell').forEach(cell => {
        cell.textContent = '';
        cell.classList.remove('winning');
      });
      modeModal.style.display = 'flex';
    });

   
    window.onload = () => {
      modeModal.style.display = 'flex';
    };
  </script>
</body>
</html>
