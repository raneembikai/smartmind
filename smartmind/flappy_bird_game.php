<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>SmartMind - Flappy Bird Horizontal Screen</title>
<style>
  * {
    box-sizing: border-box;
  }
  body, html {
    margin: 0;
    height: 100%;
    background-color: #e3f2fd;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .container {
    /* Adjusted width to accommodate the wider game screen */
    width: 720px;
    background-color: #bbdefb;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 0 25px rgba(21, 101, 192, 0.7);
    user-select: none;
    display: flex; /* Use flexbox to arrange content */
    flex-direction: column; /* Stack game, controls, score vertically */
    align-items: center; /* Center items horizontally in the container */
  }

  #game {
    position: relative;
    /* Swapped width and height for a horizontal screen */
    width: 600px; /* Game width */
    height: 400px; /* Game height */
    background-color: #90caf9;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: inset 0 0 20px rgba(13, 71, 161, 0.7);
    margin: 0 auto 20px; /* Center horizontally and add margin below */
  }

  #bird {
    position: absolute;
    width: 40px;
    height: 30px;
    background-color: #0d47a1;
    border-radius: 50% 50% 40% 40% / 60% 60% 40% 40%;
    /* Adjust initial top position to be roughly centered vertically in the new game height */
    top: 185px; /* (400px - 30px bird height) / 2 */
    left: 80px;
    transition: transform 0.1s;
  }

  .pipe {
    position: absolute;
    width: 60px; /* Pipe width remains the same */
    background-color: #1565c0;
    border-radius: 8px;
  }

  .pipe.top {
    border-radius: 8px 8px 0 0;
  }

  .pipe.bottom {
    border-radius: 0 0 8px 8px;
  }

  .controls {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    width: 100%; /* Make controls span the container width */
    max-width: 600px; /* Match game width for alignment */
  }

  button {
    background-color: #1565c0;
    color: white;
    border: none;
    padding: 14px 0;
    border-radius: 8px;
    cursor: pointer;
    font-size: 18px;
    transition: background-color 0.3s ease;
    width: 22%;
  }

  button:disabled {
    background-color: #90caf9;
    cursor: not-allowed;
  }

  button:hover:not(:disabled) {
    background-color: #0d47a1;
  }

  #score {
    font-size: 26px;
    font-weight: 700;
    color: #1565c0;
    text-align: center;
    margin-bottom: 10px;
  }

  #game-over {
    display: none;
    font-size: 22px;
    color: #b71c1c;
    text-align: center;
    margin-bottom: 10px;
  }

  .back-btn {
    text-align: center;
    margin-top: 20px; /* Add some space above the back button */
  }

  .back-btn a {
    text-decoration: none;
    background-color: #1565c0;
    color: white;
    padding: 12px 40px;
    border-radius: 8px;
    display: inline-block;
    transition: background-color 0.3s;
    font-size: 18px;
  }

  .back-btn a:hover {
    background-color: #0d47a1;
  }
</style>
</head>
<body>

<div class="container">
  <div id="game">
    <div id="bird"></div>
  </div>

  <div class="controls">
    <button id="startBtn">Start</button>
    <button id="pauseBtn" disabled>Pause</button>
    <button id="resumeBtn" disabled>Resume</button>
    <button id="playAgainBtn" style="display:none;">Play Again</button>
  </div>

  <div id="game-over">
    Game Over! Your score: <span id="final-score">0</span>
  </div>

  <div id="score">Score: 0</div>

  <div class="back-btn">
    <a href="games.php">← Back to Game Selection</a>
  </div>
</div>

<script>
  const game = document.getElementById('game');
  const bird = document.getElementById('bird');
  const scoreDisplay = document.getElementById('score');
  const gameOverDisplay = document.getElementById('game-over');
  const finalScoreDisplay = document.getElementById('final-score');

  const startBtn = document.getElementById('startBtn');
  const pauseBtn = document.getElementById('pauseBtn');
  const resumeBtn = document.getElementById('resumeBtn');
  const playAgainBtn = document.getElementById('playAgainBtn');

  // **Only change these values for the game dimensions**
  const gameWidth = 600; // Original was 400
  const gameHeight = 400; // Original was 600

  const birdSize = 30; // Bird's height
  const pipeWidth = 60; // Pipe's width
  const pipeGap = 150;
  let birdY; // Will be set in resetGame
  let birdVelocity = 0;
  const gravity = 0.6;
  const jumpStrength = -10;
  let pipes = [];
  let pipeSpeed = 3;
  let frameCount = 0;
  let score = 0;
  let gameRunning = false;
  let animationFrameId = null;

  function createPipe(xPosition) {
    const pipeTopHeight = Math.floor(Math.random() * (gameHeight - pipeGap - 100)) + 50;
    const pipeBottomHeight = gameHeight - pipeGap - pipeTopHeight;

    const pipeTop = document.createElement('div');
    pipeTop.classList.add('pipe', 'top');
    pipeTop.style.height = pipeTopHeight + 'px';
    pipeTop.style.left = xPosition + 'px';
    pipeTop.style.top = '0px';

    const pipeBottom = document.createElement('div');
    pipeBottom.classList.add('pipe', 'bottom');
    pipeBottom.style.height = pipeBottomHeight + 'px';
    pipeBottom.style.left = xPosition + 'px';
    pipeBottom.style.top = (gameHeight - pipeBottomHeight) + 'px';

    game.appendChild(pipeTop);
    game.appendChild(pipeBottom);

    return { top: pipeTop, bottom: pipeBottom, x: xPosition, passed: false };
  }

  function resetGame() {
    pipes.forEach(pipe => {
      game.removeChild(pipe.top);
      game.removeChild(pipe.bottom);
    });
    pipes = [];
    // Adjust initial birdY for the new horizontal screen height
    birdY = (gameHeight / 2) - (birdSize / 2); // Center bird vertically
    birdVelocity = 0;
    frameCount = 0;
    score = 0;
    scoreDisplay.textContent = "Score: 0";
    gameOverDisplay.style.display = 'none';
    playAgainBtn.style.display = 'none';
    startBtn.disabled = false;
    pauseBtn.disabled = true;
    resumeBtn.disabled = true;
    bird.style.top = birdY + 'px'; // Update bird's initial position
    bird.style.left = '80px'; // Keep bird's horizontal position
    bird.style.transform = `rotate(0deg)`; // Reset rotation on new game

    // Update dimensions of the game element in JS
    game.style.width = gameWidth + 'px';
    game.style.height = gameHeight + 'px';

    // Create initial pipes based on new gameWidth
    for (let i = 0; i < 3; i++) {
      pipes.push(createPipe(gameWidth + i * 200));
    }
  }

  function gameLoop() {
    if (!gameRunning) return;

    birdVelocity += gravity;
    birdY += birdVelocity;
    if (birdY < 0) birdY = 0;
    if (birdY > gameHeight - birdSize) birdY = gameHeight - birdSize;

    bird.style.top = birdY + 'px';
    bird.style.transform = `rotate(${birdVelocity * 3}deg)`;

    pipes.forEach(pipe => {
      pipe.x -= pipeSpeed;
      pipe.top.style.left = pipe.x + 'px';
      pipe.bottom.style.left = pipe.x + 'px';

      const birdLeft = 80;
      const birdRight = birdLeft + 40; // bird width is 40px
      const birdTop = birdY;
      const birdBottom = birdY + birdSize;

      const pipeLeft = pipe.x;
      const pipeRight = pipe.x + pipeWidth;

      if (birdRight > pipeLeft && birdLeft < pipeRight) {
        if (birdTop < pipe.top.offsetHeight) {
          endGame();
        }
        if (birdBottom > (gameHeight - pipe.bottom.offsetHeight)) {
          endGame();
        }
      }

      if (!pipe.passed && pipe.x + pipeWidth < birdLeft) {
        pipe.passed = true;
        score++;
        scoreDisplay.textContent = `Score: ${score}`;
      }
    });

    if (pipes.length > 0 && pipes[0].x < -pipeWidth) {
      const pipeToRemove = pipes.shift();
      game.removeChild(pipeToRemove.top);
      game.removeChild(pipeToRemove.bottom);
      const newPipeX = pipes[pipes.length - 1].x + 200;
      pipes.push(createPipe(newPipeX));
    }

    // Collision with top/bottom boundaries, now adjusted for new gameHeight
    if (birdY >= gameHeight - birdSize || birdY <= 0) {
      endGame();
    }

    frameCount++;
    animationFrameId = requestAnimationFrame(gameLoop);
  }

  function startGame() {
    resetGame();
    gameRunning = true;
    startBtn.disabled = true;
    pauseBtn.disabled = false;
    resumeBtn.disabled = true;
    playAgainBtn.style.display = 'none';
    gameOverDisplay.style.display = 'none';
    animationFrameId = requestAnimationFrame(gameLoop);
  }

  function pauseGame() {
    gameRunning = false;
    pauseBtn.disabled = true;
    resumeBtn.disabled = false;
  }

  function resumeGame() {
    if (!gameRunning) {
      gameRunning = true;
      pauseBtn.disabled = false;
      resumeBtn.disabled = true;
      animationFrameId = requestAnimationFrame(gameLoop);
    }
  }

  function endGame() {
    gameRunning = false;
    cancelAnimationFrame(animationFrameId);
    gameOverDisplay.style.display = 'block';
    finalScoreDisplay.textContent = score;
    pauseBtn.disabled = true;
    resumeBtn.disabled = true;
    playAgainBtn.style.display = 'inline-block';
  }

  startBtn.addEventListener('click', startGame);
  pauseBtn.addEventListener('click', pauseGame);
  resumeBtn.addEventListener('click', resumeGame);
  playAgainBtn.addEventListener('click', startGame);

  window.addEventListener('keydown', (e) => {
    if (gameRunning && (e.code === 'Space' || e.code === 'ArrowUp')) {
      birdVelocity = jumpStrength;
    }
  });

  // Initial setup when the page loads
  resetGame();
</script>

</body>
</html>