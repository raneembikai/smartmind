<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>SmartMind - Simon Says</title>
<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #e3f2fd;
    margin: 0;
    padding: 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100vh;
    overflow: hidden;
  }
  h1 {
    color: #1565c0;
    margin-bottom: 20px;
  }
  #game {
    display: grid;
    grid-template-columns: repeat(4, 100px);
    grid-gap: 15px;
    margin-bottom: 20px;
  }
  .color-btn {
    width: 100px;
    height: 100px;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 0 10px rgba(21, 101, 192, 0.3);
    opacity: 0.8;
    transition: opacity 0.3s, box-shadow 0.3s;
  }
  .color-btn.active {
    opacity: 1;
    box-shadow: 0 0 25px #1565c0;
  }

  .red    { background-color: #f44336; }
  .green  { background-color: #4caf50; }
  .blue   { background-color: #2196f3; }
  .yellow { background-color: #ffeb3b; }
  .orange { background-color: #ff9800; }
  .purple { background-color: #9c27b0; }
  .teal   { background-color: #009688; }
  .pink   { background-color: #e91e63; }

  #controls {
    margin-bottom: 20px;
  }
  button {
    background-color: #1565c0;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    margin: 0 8px;
    cursor: pointer;
    font-size: 16px;
  }
  button:disabled {
    background-color: #90caf9;
    cursor: not-allowed;
  }
  #message {
    color: #1565c0;
    font-size: 20px;
    height: 28px;
    margin-bottom: 10px;
  }
  #score {
    font-size: 18px;
    margin-bottom: 10px;
  }
  #timer {
    font-size: 16px;
    color: #d32f2f;
    height: 24px;
    margin-bottom: 20px;
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

<h1>🎮 Simon Says</h1>

<div id="game">
  <div class="color-btn red" data-color="red"></div>
  <div class="color-btn green" data-color="green"></div>
  <div class="color-btn blue" data-color="blue"></div>
  <div class="color-btn yellow" data-color="yellow"></div>
  <div class="color-btn orange" data-color="orange"></div>
  <div class="color-btn purple" data-color="purple"></div>
  <div class="color-btn teal" data-color="teal"></div>
  <div class="color-btn pink" data-color="pink"></div>
</div>

<div id="controls">
  <button id="startBtn">Start</button>
  <button id="pauseBtn" disabled>Pause</button>
  <button id="resumeBtn" disabled>Resume</button>
</div>

<div id="message">Press Start to play!</div>
<div id="score">Score: 0</div>
<div id="timer"></div>

<div class="back-btn">
  <a href="gamesPro.php">← Back to Game Selection</a>
</div>

<script>
const colors = ['red', 'green', 'blue', 'yellow', 'orange', 'purple', 'teal', 'pink'];
const baseFrequencies = [261.63, 293.66, 329.63, 349.23, 392.00, 440.00, 493.88, 523.25];
const buttons = document.querySelectorAll('.color-btn');
const startBtn = document.getElementById('startBtn');
const pauseBtn = document.getElementById('pauseBtn');
const resumeBtn = document.getElementById('resumeBtn');
const message = document.getElementById('message');
const scoreDisplay = document.getElementById('score');
const timerDisplay = document.getElementById('timer');

let sequence = [], playerSequence = [], score = 0;
let playingSequence = false, paused = false, timeoutIds = [], playerTimer = null;
let timeLeft = 0;
let colorTones = {};

function shuffleArray(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [arr[i], arr[j]] = [arr[j], arr[i]];
  }
  return arr;
}

function assignRandomTones() {
  const freqs = shuffleArray([...baseFrequencies]);
  colors.forEach((color, i) => colorTones[color] = freqs[i]);
}
assignRandomTones();

const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

function playTone(freq, duration = 400) {
  return new Promise(resolve => {
    const oscillator = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    oscillator.type = 'sine';
    oscillator.frequency.setValueAtTime(freq, audioCtx.currentTime);
    oscillator.connect(gain);
    gain.connect(audioCtx.destination);
    oscillator.start();

    gain.gain.setValueAtTime(1, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration / 1000);

    setTimeout(() => {
      oscillator.stop();
      resolve();
    }, duration);
  });
}

function enableButtons(enable) {
  buttons.forEach(btn => btn.style.pointerEvents = enable ? 'auto' : 'none');
}

function resetGame() {
  sequence = [];
  playerSequence = [];
  score = 0;
  scoreDisplay.textContent = 'Score: 0';
  message.textContent = 'Press Start to play!';
  timerDisplay.textContent = '';
  startBtn.disabled = false;
  pauseBtn.disabled = true;
  resumeBtn.disabled = true;
  clearTimeouts();
  clearPlayerTimer();
  enableButtons(false);
}

function clearTimeouts() {
  timeoutIds.forEach(id => clearTimeout(id));
  timeoutIds = [];
}
function clearPlayerTimer() {
  if (playerTimer) clearInterval(playerTimer);
  playerTimer = null;
  timerDisplay.textContent = '';
}

async function flashButton(color, short = false) {
  const btn = document.querySelector(`.color-btn.${color}`);
  btn.classList.add('active');
  await playTone(colorTones[color], short ? 250 : 400);
  await new Promise(r => setTimeout(r, short ? 100 : 200));
  btn.classList.remove('active');
  await new Promise(r => setTimeout(r, short ? 100 : 150));
}

function shouldDoubleFlash() {
  return Math.random() < Math.min(score * 0.05, 0.3);
}

async function playSequence() {
  playingSequence = true;
  enableButtons(false);
  message.textContent = 'Watch the sequence...';
  let flashDur = Math.max(400 - score * 15, 150);
  let pauseDur = Math.max(150 - score * 10, 100);

  for (const item of sequence) {
    if (paused) await new Promise(r => setTimeout(r, 100));
    if (typeof item === 'string') {
      await flashButton(item, flashDur <= 250);
    } else {
      for (const c of item) await flashButton(c, true);
    }
    await new Promise(r => setTimeout(r, pauseDur));
  }
  playingSequence = false;
  enableButtons(true);
  message.textContent = 'Your turn!';
  startPlayerTimer();
}

function addToSequence() {
  if (shouldDoubleFlash()) {
    let a = colors[Math.floor(Math.random() * colors.length)];
    let b = colors[Math.floor(Math.random() * colors.length)];
    while (a === b) b = colors[Math.floor(Math.random() * colors.length)];
    sequence.push([a, b]);
  } else {
    sequence.push(colors[Math.floor(Math.random() * colors.length)]);
  }
}

function startPlayerTimer() {
  clearPlayerTimer();
  timeLeft = Math.max(3 - score * 0.1, 1.5);
  timerDisplay.textContent = `⏱️ ${timeLeft.toFixed(1)}s`;
  playerTimer = setInterval(() => {
    timeLeft -= 0.1;
    timerDisplay.textContent = `⏱️ ${timeLeft.toFixed(1)}s`;
    if (timeLeft <= 0) {
      clearInterval(playerTimer);
      timerDisplay.textContent = '';
      message.textContent = '⛔ Time’s up!';
      enableButtons(false);
    }
  }, 100);
}

buttons.forEach(btn => {
  btn.addEventListener('click', () => {
    if (playingSequence || paused) return;
    const color = btn.dataset.color;
    flashButton(color, true);
    playerSequence.push(color);

    const idx = playerSequence.length - 1;
    const expected = sequence[idx];

    if (Array.isArray(expected)) {
      const flatExpected = expected;
      const flatPlayer = playerSequence.slice(idx - 1, idx + 1);
      if (flatPlayer.join() === flatExpected.join()) {
        if (flatPlayer.length === sequence.flat().length) {
          score++;
          scoreDisplay.textContent = `Score: ${score}`;
          playerSequence = [];
          addToSequence();
          setTimeout(playSequence, 1000);
        }
      } else if (flatPlayer.length === 2) {
        message.textContent = '❌ Wrong!';
        enableButtons(false);
      }
    } else {
      if (color !== expected) {
        message.textContent = '❌ Wrong!';
        enableButtons(false);
      } else if (playerSequence.length === sequence.length) {
        score++;
        scoreDisplay.textContent = `Score: ${score}`;
        playerSequence = [];
        addToSequence();
        setTimeout(playSequence, 1000);
      }
    }
  });
});

startBtn.onclick = () => {
  resetGame();
  assignRandomTones();
  addToSequence();
  startBtn.disabled = true;
  pauseBtn.disabled = false;
  playSequence();
};
pauseBtn.onclick = () => {
  paused = true;
  pauseBtn.disabled = true;
  resumeBtn.disabled = false;
  message.textContent = '⏸ Paused';
};
resumeBtn.onclick = () => {
  paused = false;
  resumeBtn.disabled = true;
  pauseBtn.disabled = false;
  message.textContent = 'Resuming...';
};
</script>

</body>
</html>
