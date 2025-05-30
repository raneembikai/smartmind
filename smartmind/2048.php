<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>2048 Game</title>
  <style>
    body {
      background: linear-gradient(135deg, #dbeafe, #eff6ff);
      font-family: 'Segoe UI', sans-serif;
      color: #1e3a8a;
      text-align: center;
      margin: 0;
      padding: 0;
    }
    h1 {
      margin-top: 30px;
      color: #1e40af;
    }
    .container {
      margin-top: 50px;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(4, 100px);
      grid-gap: 12px;
      justify-content: center;
      margin-bottom: 30px;
    }
    .tile {
      width: 100px;
      height: 100px;
      background-color: #bfdbfe;
      font-size: 2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #1e3a8a;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 50, 0.1);
      transition: 0.3s;
    }
    .tile.empty {
      background-color: #e0f2fe;
      color: transparent;
    }
    .tile:active {
      transform: scale(0.98);
    }
    .button {
      padding: 12px 25px;
      font-size: 18px;
      background-color: #3b82f6;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.3s;
      text-decoration: none;
    }
    .button:hover {
      background-color: #2563eb;
    }
    .button:active {
      transform: scale(0.96);
    }
    .info {
      margin-top: 20px;
      font-size: 18px;
    }
  </style>
</head>
<body>

<h1>🧩 2048 Soft Blue Edition</h1>

<div class="container">
  <div class="grid" id="game2048Grid">
    <!-- Tiles will be created dynamically -->
  </div>
  <div class="info">
    <button class="button" onclick="window.location.href='games.php'">Back to Game Selection</button>
  </div>
</div>

<script>
  const grid = document.getElementById('game2048Grid');
  let tiles = Array(16).fill(null);

  for (let i = 0; i < 16; i++) {
    const tile = document.createElement('div');
    tile.classList.add('tile');
    tile.classList.add('empty');
    tile.dataset.index = i;
    grid.appendChild(tile);
  }

  function placeTile() {
    const emptyIndices = tiles.map((tile, index) => !tile ? index : -1).filter(index => index !== -1);
    if (emptyIndices.length === 0) return;
    const randomIndex = emptyIndices[Math.floor(Math.random() * emptyIndices.length)];
    tiles[randomIndex] = Math.random() < 0.9 ? 2 : 4;
    updateGrid();
  }

  function updateGrid() {
    const tileElements = document.querySelectorAll('.tile');
    tileElements.forEach((tileElement, index) => {
      const value = tiles[index];
      if (value !== null) {
        tileElement.textContent = value;
        tileElement.classList.remove('empty');
      } else {
        tileElement.textContent = '';
        tileElement.classList.add('empty');
      }
    });
  }

  function moveTiles(direction) {
    let moved = false;
    switch (direction) {
      case 'up': moved = moveUp(); break;
      case 'down': moved = moveDown(); break;
      case 'left': moved = moveLeft(); break;
      case 'right': moved = moveRight(); break;
    }
    if (moved) placeTile();
  }

  function moveUp() {
    let moved = false;
    for (let col = 0; col < 4; col++) {
      const column = [];
      for (let row = 0; row < 4; row++) column.push(tiles[row * 4 + col]);
      const newColumn = shift(column);
      for (let row = 0; row < 4; row++) {
        if (newColumn[row] !== tiles[row * 4 + col]) moved = true;
        tiles[row * 4 + col] = newColumn[row];
      }
    }
    updateGrid();
    return moved;
  }

  function moveDown() {
    let moved = false;
    for (let col = 0; col < 4; col++) {
      const column = [];
      for (let row = 0; row < 4; row++) column.push(tiles[row * 4 + col]);
      const newColumn = shift(column.reverse()).reverse();
      for (let row = 0; row < 4; row++) {
        if (newColumn[row] !== tiles[row * 4 + col]) moved = true;
        tiles[row * 4 + col] = newColumn[row];
      }
    }
    updateGrid();
    return moved;
  }

  function moveLeft() {
    let moved = false;
    for (let row = 0; row < 4; row++) {
      const rowValues = tiles.slice(row * 4, row * 4 + 4);
      const newRow = shift(rowValues);
      for (let col = 0; col < 4; col++) {
        if (newRow[col] !== tiles[row * 4 + col]) moved = true;
        tiles[row * 4 + col] = newRow[col];
      }
    }
    updateGrid();
    return moved;
  }

  function moveRight() {
    let moved = false;
    for (let row = 0; row < 4; row++) {
      const rowValues = tiles.slice(row * 4, row * 4 + 4);
      const newRow = shift(rowValues.reverse()).reverse();
      for (let col = 0; col < 4; col++) {
        if (newRow[col] !== tiles[row * 4 + col]) moved = true;
        tiles[row * 4 + col] = newRow[col];
      }
    }
    updateGrid();
    return moved;
  }

  function shift(arr) {
    const filtered = arr.filter(val => val !== null);
    for (let i = 0; i < filtered.length - 1; i++) {
      if (filtered[i] === filtered[i + 1]) {
        filtered[i] *= 2;
        filtered[i + 1] = null;
      }
    }
    const merged = filtered.filter(val => val !== null);
    return [...merged, ...Array(4 - merged.length).fill(null)];
  }

  document.addEventListener('keydown', (e) => {
    switch (e.key) {
      case 'ArrowUp': moveTiles('up'); break;
      case 'ArrowDown': moveTiles('down'); break;
      case 'ArrowLeft': moveTiles('left'); break;
      case 'ArrowRight': moveTiles('right'); break;
    }
  });

  // Start game
  placeTile();
  placeTile();
</script>

</body>
</html>
