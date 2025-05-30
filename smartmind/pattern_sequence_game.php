<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartMind - Color Sorting Puzzle</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #e3f2fd;
            margin: 0;
            padding: 30px;
        }
        h1 {
            text-align: center;
            color: #1565c0;
            margin-bottom: 30px;
        }
        .tube-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        .tube {
            width: 60px;
            height: 240px;
            background-color: #bbdefb;
            border: 2px solid #1565c0;
            border-radius: 10px;
            display: flex;
            flex-direction: column-reverse;
            justify-content: flex-start;
            cursor: pointer;
            padding: 5px;
        }
        .color-block {
            height: 50px;
            margin: 3px 0;
            border-radius: 4px;
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

<h1>🎨 Color Sorting Puzzle</h1>
<div class="tube-container" id="tube-container"></div>
<div class="game-over" id="game-over" style="display: none;">
    🎉 Well done! All tubes are sorted!
    <br><button onclick="resetGame()">Play Again</button>
</div>
<div class="back-btn">
    <a href="games.php">← Back to Game Selection</a>
</div>

<script>
    const colors = ["red", "blue", "green", "yellow"];
    let tubes = [];
    let selectedTube = null;

    function initGame() {
        const container = document.getElementById("tube-container");
        container.innerHTML = "";
        selectedTube = null;

        const blocks = colors.flatMap(color => [color, color, color, color]);
        blocks.sort(() => Math.random() - 0.5);

        tubes = [[], [], [], [], [], []];
        let index = 0;
        for (let i = 0; i < 4; i++) {
            for (let j = 0; j < 4; j++) {
                tubes[i].push(blocks[index++]);
            }
        }

        renderTubes();
    }

    function renderTubes() {
        const container = document.getElementById("tube-container");
        container.innerHTML = "";

        tubes.forEach((tube, index) => {
            const tubeDiv = document.createElement("div");
            tubeDiv.className = "tube";
            tubeDiv.dataset.index = index;
            if (selectedTube === index) tubeDiv.style.border = "3px solid #0d47a1";

            tube.forEach(color => {
                const block = document.createElement("div");
                block.className = "color-block";
                block.style.backgroundColor = color;
                tubeDiv.appendChild(block);
            });

            tubeDiv.addEventListener("click", () => handleTubeClick(index));
            container.appendChild(tubeDiv);
        });
    }

    function handleTubeClick(index) {
        if (selectedTube === null && tubes[index].length > 0) {
            selectedTube = index;
        } else if (selectedTube !== null && selectedTube !== index) {
            const fromTube = tubes[selectedTube];
            const toTube = tubes[index];
            if (toTube.length < 4 && (toTube.length === 0 || fromTube[fromTube.length - 1] === toTube[toTube.length - 1])) {
                toTube.push(fromTube.pop());
                if (checkWin()) document.getElementById("game-over").style.display = "block";
            }
            selectedTube = null;
        } else {
            selectedTube = null;
        }
        renderTubes();
    }

    function checkWin() {
        return tubes.every(tube => tube.length === 0 || (tube.length === 4 && tube.every(color => color === tube[0])));
    }

    function resetGame() {
        document.getElementById("game-over").style.display = "none";
        initGame();
    }

    initGame();
</script>

</body>
</html>
