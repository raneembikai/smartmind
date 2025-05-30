<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Maze Logic Pathfinder - Hard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f4f8;
      margin: 0;
      padding: 20px;
      text-align: center;
    }
    h1 {
      color: #0d47a1;
    }
    .maze-container {
      display: grid;
      justify-content: center;
      margin: 20px auto;
      gap: 1px;
    }
    .cell {
      width: 20px;
      height: 20px;
      box-sizing: border-box;
      background-color: #bbdefb;
      border: 1px solid #90caf9;
    }
    .wall {
      background-color: #0d47a1;
    }
    .player {
      background-color: #4fc3f7;
    }
    .goal {
      background-color: #66bb6a;
    }
    #win {
      font-size: 20px;
      color: green;
      margin-top: 20px;
    }
    button#back-btn {
      margin-top: 30px;
      padding: 10px 20px;
      font-size: 16px;
      background-color: #0d47a1;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button#back-btn:hover {
      background-color: #08306b;
    }
  </style>
</head>
<body>

  <h1>🧩 Maze Logic Pathfinder</h1>
  <div class="maze-container" id="maze"></div>
  <div id="win"></div>
  
  <button id="back-btn" onclick="goBack()">Back to Game Selection</button>

  <script>
    const rows = 21; // must be odd
    const cols = 21; // must be odd
    const maze = [];
    let player = { x: 1, y: 1 };

    const mazeContainer = document.getElementById("maze");
    mazeContainer.style.gridTemplateColumns = `repeat(${cols}, 20px)`;

    // Initialize maze with walls
    for (let y = 0; y < rows; y++) {
      maze[y] = [];
      for (let x = 0; x < cols; x++) {
        maze[y][x] = 1;
      }
    }

    function shuffle(arr) {
      for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
      }
      return arr;
    }

    function generateMaze(x, y) {
      const directions = shuffle([
        [0, -2], [0, 2], [-2, 0], [2, 0]
      ]);

      for (const [dx, dy] of directions) {
        const nx = x + dx;
        const ny = y + dy;

        if (
          ny > 0 && ny < rows - 1 &&
          nx > 0 && nx < cols - 1 &&
          maze[ny][nx] === 1
        ) {
          maze[ny][nx] = 0;
          maze[y + dy / 2][x + dx / 2] = 0;
          generateMaze(nx, ny);
        }
      }
    }

    maze[1][1] = 0;
    generateMaze(1, 1);
    maze[rows - 2][cols - 2] = 2; // goal

    function renderMaze() {
      mazeContainer.innerHTML = "";
      for (let y = 0; y < rows; y++) {
        for (let x = 0; x < cols; x++) {
          const div = document.createElement("div");
          div.classList.add("cell");
          if (maze[y][x] === 1) div.classList.add("wall");
          if (maze[y][x] === 2) div.classList.add("goal");
          if (x === player.x && y === player.y) div.classList.add("player");
          mazeContainer.appendChild(div);
        }
      }
    }

    function move(dx, dy) {
      const nx = player.x + dx;
      const ny = player.y + dy;

      if (
        nx >= 0 && nx < cols &&
        ny >= 0 && ny < rows &&
        maze[ny][nx] !== 1
      ) {
        player = { x: nx, y: ny };
        renderMaze();
        if (maze[ny][nx] === 2) {
          document.getElementById("win").textContent = "🎉 You reached the goal!";
          document.removeEventListener("keydown", handleKey);
        }
      }
    }

    function handleKey(e) {
      switch (e.key) {
        case "ArrowUp": move(0, -1); break;
        case "ArrowDown": move(0, 1); break;
        case "ArrowLeft": move(-1, 0); break;
        case "ArrowRight": move(1, 0); break;
      }
    }

    function goBack() {
      window.location.href = "gamesPro.php";
    }

    document.addEventListener("keydown", handleKey);
    renderMaze();
  </script>

</body>
</html>
