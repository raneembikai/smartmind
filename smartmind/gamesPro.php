<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Game Selection</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to right, #e3f2fd, #bbdefb);
      margin: 0;
      padding: 0;
      text-align: center;
      color: #0d47a1;
    }

    h1 {
      padding-top: 30px;
      font-size: 2.5rem;
      color: #0d47a1;
    }

    .container {
      padding: 40px 20px;
    }

    .game-button {
      padding: 15px 30px;
      font-size: 18px;
      margin: 15px;
      background-color: #2196f3;
      color: white;
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(33, 150, 243, 0.3);
      cursor: pointer;
      transition: all 0.3s ease-in-out;
    }

    .game-button:hover {
      background-color: #1976d2;
      box-shadow: 0 6px 14px rgba(25, 118, 210, 0.4);
    }

    .description {
      margin-top: 30px;
      background-color: #ffffff;
      border-radius: 16px;
      padding: 25px;
      max-width: 700px;
      margin-left: auto;
      margin-right: auto;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
      color: #333;
    }

    .description h2 {
      color: #1976d2;
    }

    .back-button {
      display: inline-block;
      margin-top: 40px;
      padding: 12px 24px;
      font-size: 18px;
      background-color: #0d47a1;
      color: white;
      border-radius: 10px;
      text-decoration: none;
      transition: background-color 0.3s ease-in-out;
    }

    .back-button:hover {
      background-color: #1565c0;
    }

    @media (max-width: 600px) {
      .game-button {
        width: 100%;
        margin: 10px 0;
      }
    }
  </style>
</head>
<body>

  <h1>🎮 Choose Your Game</h1>

  <div class="container">
    <button class="game-button" id="spaceShooterBtn">Play Space Shooter 🚀</button>
    <button class="game-button" id="memoryMatchBtn">Play Memory Card Match 🃏</button>
    <button class="game-button" id="ticTacToeBtn">Play Tic-Tac-Toe ❌⭕</button>
    <button class="game-button" id="game2048Btn">Play 2048 🧩</button>
    <button class="game-button" id="flappyBirdBtn">Play Flappy Bird 🐤</button>
    <button class="game-button" id="sortColorsBtn">Play Sort Colors 🎨</button>
    <button class="game-button" id="mazeGameBtn">Play Maze Game 🧱</button>
    <button class="game-button" id="patternGameBtn">Play Pattern Sequence 🔢</button>

    <div class="description">
      <div id="spaceShooterDesc">
        <h2>Space Shooter 🚀</h2>
        <p>Control a spaceship and shoot asteroids to score points! Avoid crashing into asteroids and keep playing to get the highest score.</p>
      </div>

      <div id="memoryMatchDesc">
        <h2>Memory Card Match 🃏</h2>
        <p>Flip cards to match pairs. Improve your memory and see how fast you can find all the pairs!</p>
      </div>

      <div id="ticTacToeDesc">
        <h2>Tic-Tac-Toe ❌⭕</h2>
        <p>Take turns with another player to place 'X' or 'O' on the grid. The first to get three in a row wins!</p>
      </div>

      <div id="game2048Desc">
        <h2>2048 🧩</h2>
        <p>Combine tiles to reach 2048. The goal is to merge tiles of the same number by sliding them in four directions. Can you reach 2048?</p>
      </div>

      <div id="flappyBirdDesc">
        <h2>Flappy Bird 🐤</h2>
        <p>Tap to make the bird fly through pipes without crashing. Time your taps and beat your high score!</p>
      </div>

      <div id="sortColorsDesc">
        <h2>Sort Colors 🎨</h2>
        <p>Sort the colored balls into matching tubes. Use logic and planning to complete each level!</p>
      </div>

      <div id="mazeGameDesc">
        <h2>Maze Game 🧱</h2>
        <p>Find your way through complex mazes. Navigate from start to finish without hitting dead ends!</p>
      </div>

      <div id="patternGameDesc">
        <h2>Pattern Sequence 🔢</h2>
        <p>Remember and repeat the pattern shown. Each round gets harder. Test your memory and reaction speed!</p>
      </div>

      <a href="profile.php" class="back-button">⬅ Back to Dashboard</a>
    </div>
  </div>

  <script>
    const buttons = {
      spaceShooterBtn: 'spaceShooterpro.php',
      memoryMatchBtn: 'memory_card_matchpro.php',
      ticTacToeBtn: 'XOpro.php',
      game2048Btn: '2048pro.php',
      flappyBirdBtn: 'flappy_bird_gamepro.php',
      sortColorsBtn: 'pattern_sequence_gamepro.php',
      mazeGameBtn: 'maze_gamepro.php',
      patternGameBtn: 'simons_gamepro.php'
    };

    const descriptions = {
      spaceShooterBtn: 'spaceShooterDesc',
      memoryMatchBtn: 'memoryMatchDesc',
      ticTacToeBtn: 'ticTacToeDesc',
      game2048Btn: 'game2048Desc',
      flappyBirdBtn: 'flappyBirdDesc',
      sortColorsBtn: 'sortColorsDesc',
      mazeGameBtn: 'mazeGameDesc',
      patternGameBtn: 'patternGameDesc'
    };

    Object.keys(buttons).forEach(id => {
      document.getElementById(id).onclick = () => {
        window.location.href = buttons[id];
      };

      document.getElementById(id).onmouseover = () => {
        Object.values(descriptions).forEach(descId => {
          document.getElementById(descId).style.display = 'none';
        });
        document.getElementById(descriptions[id]).style.display = 'block';
      };
    });

    // Show default description
    window.onload = () => {
      Object.values(descriptions).forEach(descId => {
        document.getElementById(descId).style.display = 'none';
      });
      document.getElementById('spaceShooterDesc').style.display = 'block';
    };
  </script>

</body>
</html>
