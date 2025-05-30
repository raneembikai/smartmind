<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Space Shooter Game 🚀</title>
  <style>
    body {
      margin: 0;
      overflow: hidden;
      background: linear-gradient(to bottom right, #cbe4f9, #e0f7fa);
      color: #0d47a1;
      font-family: 'Segoe UI', sans-serif;
      text-align: center;
    }

    h1 {
      margin-top: 20px;
      font-size: 2.8rem;
      color: #01579b;
      text-shadow: 1px 1px 2px #fff;
    }

    #gameCanvas {
      display: block;
      margin: 20px auto;
      background-color: #000;
      border: 4px solid #64b5f6;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }

    #controls {
      margin-top: 20px;
    }

    button {
      padding: 12px 25px;
      margin: 10px;
      font-size: 18px;
      background-color: #42a5f5;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #1e88e5;
    }

    @media (max-width: 700px) {
      #gameCanvas {
        width: 100%;
        height: auto;
      }
    }
  </style>
</head>
<body>

<h1>Space Shooter 🚀</h1>
<canvas id="gameCanvas" width="600" height="400"></canvas>

<div id="controls">
  <button id="startBtn">Start</button>
  <button id="pauseBtn" disabled>Pause</button>
  <button onclick="window.location.href='games.php'">🎮 Back to Game Selection</button>
</div>

<script>
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');

// Game variables
let spaceship;
let asteroids = [];
let bullets = [];
let score = 0;
let gameInterval;
let gameRunning = false;
let paused = false;

// Spaceship
const spaceshipWidth = 40;
const spaceshipHeight = 40;
const spaceshipSpeed = 5;
let spaceshipX = canvas.width / 2 - spaceshipWidth / 2;
let spaceshipY = canvas.height - spaceshipHeight - 10;
let spaceshipDx = 0;

// Asteroids
const asteroidWidth = 50;
const asteroidHeight = 50;
const asteroidSpeed = 3;

// Controls
const startBtn = document.getElementById('startBtn');
const pauseBtn = document.getElementById('pauseBtn');

startBtn.addEventListener('click', startGame);
pauseBtn.addEventListener('click', togglePause);
document.addEventListener('keydown', moveSpaceship);
document.addEventListener('keyup', stopSpaceship);
document.addEventListener('keydown', shootBullet);

function startGame() {
  spaceshipX = canvas.width / 2 - spaceshipWidth / 2;
  spaceshipY = canvas.height - spaceshipHeight - 10;
  spaceshipDx = 0;
  asteroids = [];
  bullets = [];
  score = 0;
  gameRunning = true;
  paused = false;
  startBtn.disabled = true;
  pauseBtn.disabled = false;
  pauseBtn.innerText = "Pause";
  gameInterval = setInterval(gameLoop, 1000 / 60);
}

function togglePause() {
  paused = !paused;
  if (paused) {
    clearInterval(gameInterval);
    pauseBtn.innerText = "Resume";
  } else {
    gameInterval = setInterval(gameLoop, 1000 / 60);
    pauseBtn.innerText = "Pause";
  }
}

function moveSpaceship(e) {
  if (gameRunning && !paused) {
    if (e.key === 'ArrowLeft' || e.key === 'a') {
      spaceshipDx = -spaceshipSpeed;
    } else if (e.key === 'ArrowRight' || e.key === 'd') {
      spaceshipDx = spaceshipSpeed;
    }
  }
}

function stopSpaceship(e) {
  if (e.key === 'ArrowLeft' || e.key === 'ArrowRight' || e.key === 'a' || e.key === 'd') {
    spaceshipDx = 0;
  }
}

function shootBullet(e) {
  if (e.key === ' ' && gameRunning && !paused) {
    bullets.push({ x: spaceshipX + spaceshipWidth / 2 - 2.5, y: spaceshipY, width: 5, height: 10, speed: 5 });
  }
}

function drawSpaceship() {
  ctx.fillStyle = "#fff";
  ctx.fillRect(spaceshipX, spaceshipY, spaceshipWidth, spaceshipHeight);
}

function drawAsteroids() {
  for (let i = 0; i < asteroids.length; i++) {
    ctx.fillStyle = "#b0bec5";
    ctx.fillRect(asteroids[i].x, asteroids[i].y, asteroidWidth, asteroidHeight);
    asteroids[i].y += asteroidSpeed;

    // Collision with spaceship
    if (
      spaceshipX < asteroids[i].x + asteroidWidth &&
      spaceshipX + spaceshipWidth > asteroids[i].x &&
      spaceshipY < asteroids[i].y + asteroidHeight &&
      spaceshipY + spaceshipHeight > asteroids[i].y
    ) {
      gameOver();
    }

    if (asteroids[i].y > canvas.height) {
      asteroids.splice(i, 1);
      score++;
    }
  }
}

function drawBullets() {
  for (let i = 0; i < bullets.length; i++) {
    ctx.fillStyle = "#ffeb3b";
    ctx.fillRect(bullets[i].x, bullets[i].y, bullets[i].width, bullets[i].height);
    bullets[i].y -= bullets[i].speed;

    for (let j = 0; j < asteroids.length; j++) {
      if (
        bullets[i] &&
        bullets[i].x < asteroids[j].x + asteroidWidth &&
        bullets[i].x + bullets[i].width > asteroids[j].x &&
        bullets[i].y < asteroids[j].y + asteroidHeight &&
        bullets[i].y + bullets[i].height > asteroids[j].y
      ) {
        asteroids.splice(j, 1);
        bullets.splice(i, 1);
        score += 5;
        break;
      }
    }

    if (bullets[i] && bullets[i].y < 0) {
      bullets.splice(i, 1);
    }
  }
}

function generateAsteroids() {
  if (Math.random() < 0.02) {
    let asteroidX = Math.random() * (canvas.width - asteroidWidth);
    asteroids.push({ x: asteroidX, y: -asteroidHeight });
  }
}

function drawScore() {
  ctx.fillStyle = "#fff";
  ctx.font = "20px Arial";
  ctx.fillText("Score: " + score, 10, 30);
}

function gameOver() {
  gameRunning = false;
  clearInterval(gameInterval);
  alert(`💥 Game Over!\nYour Score: ${score}`);
  startBtn.disabled = false;
  pauseBtn.disabled = true;
}

function gameLoop() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  drawSpaceship();
  drawAsteroids();
  drawBullets();
  generateAsteroids();
  drawScore();

  spaceshipX += spaceshipDx;
  if (spaceshipX < 0) spaceshipX = 0;
  if (spaceshipX + spaceshipWidth > canvas.width) spaceshipX = canvas.width - spaceshipWidth;
}
</script>

</body>
</html>
