<?php
session_start();
include("inc/connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $con->prepare("SELECT name, iq_score, level FROM logintb WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $iq_score, $user_level);
$stmt->fetch();
$stmt->close();

// Now, based on the current level, load the training content
if ($user_level == 1) {

} elseif ($user_level == 2) {

} elseif ($user_level == 3) {

}


function get_last_score($user_id, $category, $con) {
    $stmt = $con->prepare("SELECT score FROM user_scores WHERE user_id = ? AND subject = ? ORDER BY timestamp DESC LIMIT 1");
    $stmt->bind_param("is", $user_id, $category);
    $stmt->execute();
    $stmt->bind_result($last_score);
    $stmt->fetch();
    $stmt->close();
    return $last_score;
}

$math_score = get_last_score($user_id, 'Math', $con);
$pattern_score = get_last_score($user_id, 'Pattern', $con);
$deduction_score = get_last_score($user_id, 'Deduction', $con);

$all_completed = ($math_score !== null && $pattern_score !== null && $deduction_score !== null);

// Function to update user level
function update_user_level($user_id, $con) {
    $stmt = $con->prepare("UPDATE logintb SET level = level + 1 WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// Check if all levels are completed and user needs to level up
if ($all_completed) {
    $max_level = 3; // Define your maximum level here
    if ($user_level < $max_level) {
        // Increment the user's level in the database
        update_user_level($user_id, $con);

        // Clear user_scores for the next level
        $clear = $con->prepare("DELETE FROM user_scores WHERE user_id = ?");
        $clear->bind_param("i", $user_id);
        $clear->execute();
        $clear->close();

        // Store the new level in a session variable to display the popup on the next page load
        $_SESSION['level_up_to'] = $user_level + 1; // Store the *new* level
        header("Location: start_training.php"); // Redirect back to this page (or wherever you want the popup to show)
        exit;
    } elseif ($user_level == $max_level) {
        // If they are at the max level, redirect to a thank you page or similar
        header("Location: thank_you.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartMind Training</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Keyframes for smoother animations */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        /* Enhanced Category Card Styling */
        .category-card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border-radius: 15px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .category-card-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 15px 15px 0 0;
        }

        .category-card-content {
            padding: 20px;
            text-align: center;
        }

        .category-card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #374151; /* Dark gray */
            margin-bottom: 10px;
        }

        .category-card-description {
            color: #6b7280; /* Medium gray */
            margin-bottom: 15px;
        }

        .category-card-status {
            background-color: #f0f9ff; /* Light blue */
            color: #38bdf8; /* Blue */
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
            font-weight: bold;
        }

        /* User Info Box - Left Positioned */
        .user-info-container {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 300px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        .user-name {
            font-size: 2rem;
            font-weight: bold;
            color: #4c6ef5; /* A nice blue */
            margin-bottom: 8px;
        }

        .user-stats {
            color: #718096; /* Slate gray */
            margin-bottom: 6px;
        }

        .user-level {
            font-size: 1.2rem;
            font-weight: bold;
            color:rgb(3, 20, 14); /* Teal */
        }

        /* Back Button Styling - Top Right Positioned */
        .back-button-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #64748b; /* Cool gray */
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .back-button:hover {
            background-color: #4a5568;
            transform: scale(1.03);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        /* Main Content Area - Adjusted Padding for Overlap */
        .main-content {
            padding: 60px 20px 40px 20px; /* Added top padding to avoid overlap */
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
            width: 95%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .category-grid {
            display: grid;
            grid-template-columns: 1fr; /* Default to single column for small screens */
            gap: 20px;
        }

        @media (min-width: 768px) {
            .category-grid {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            }
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2d3748; /* Very dark gray */
            margin-bottom: 30px;
            text-align: center;
        }

        /* --- Styles for the Level Up Modal --- */
        .level-up-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent black background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000; /* Ensure it's on top of everything */
            opacity: 0; /* Start hidden */
            visibility: hidden; /* Start hidden */
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .level-up-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .level-up-modal-content {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transform: scale(0.8); /* Start smaller */
            transition: transform 0.3s ease;
            /* Responsive adjustments for the modal content */
            width: 90%; /* Take up 90% width on small screens */
            max-width: 500px; /* Max width for larger screens */
            box-sizing: border-box; /* Include padding in width calculation */
        }

        .level-up-modal-overlay.show .level-up-modal-content {
            transform: scale(1); /* Scale up when shown */
        }

        .level-up-modal-content h3 {
            font-size: 2.2rem;
            color:rgb(38, 152, 201);
            margin-bottom: 15px;
            font-weight: 700;
        }

        .level-up-modal-content p {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 25px;
        }

        .level-up-modal-content button {
            background-color:rgb(38, 152, 201);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }

        .level-up-modal-content button:hover {
            background-color: rgb(37, 148, 196);
        }

        /* Mobile specific adjustments */
        @media (max-width: 767px) {
            .user-info-container,
            .back-button-container {
                position: static; /* Remove absolute positioning */
                width: auto; /* Allow full width */
                margin: 20px auto 0 auto; /* Center with margin, add top margin */
                padding: 15px 20px; /* Adjust padding */
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); /* Lighter shadow */
            }

            .user-info-container {
                order: -1; /* Place user info above main content */
            }

            .main-content {
                padding-top: 30px; /* Reduce top padding on mobile */
                width: 100%; /* Full width */
                margin: 0; /* Remove horizontal margin */
            }

            .category-grid {
                grid-template-columns: 1fr; /* Ensure single column layout */
                padding: 0 15px; /* Add some horizontal padding */
            }

            .section-title {
                font-size: 2rem; /* Smaller font size for title */
            }

            .level-up-modal-content {
                padding: 25px; /* Smaller padding for modal on mobile */
                width: 95%; /* Wider on very small screens */
            }

            .level-up-modal-content h3 {
                font-size: 1.8rem; /* Smaller heading for modal */
            }

            .level-up-modal-content p {
                font-size: 1rem; /* Smaller text for modal */
            }

            .level-up-modal-content button {
                padding: 10px 20px; /* Smaller button padding */
                font-size: 1rem; /* Smaller button font size */
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-poppins min-h-screen relative flex flex-col items-center">
    <div class="user-info-container fade-in">
        <p class="user-name"><?= htmlspecialchars($name) ?></p>
        <p class="user-level">🎯 Level: <?= $user_level ?></p>
    </div>

    <div class="back-button-container fade-in">
        <a href="profile.php" class="back-button">← Back</a>
    </div>

    <div class="main-content">
        <h2 class="section-title fade-in">Choose Your Training 🧠</h2>

        <div class="category-grid">

            <a href="math_training.php" class="fade-in">
                <div class="category-card">
                    <img src="assets/img/math.jpg" alt="Math" class="category-card-image">
                    <div class="category-card-content">
                        <h3 class="category-card-title">📐 Math</h3>
                        <p class="category-card-description">Sharpen your problem-solving skills with engaging math challenges.</p>
                        <?php if ($math_score !== null): ?>
                            <div class="category-card-status">
                                ✅ Completed: <strong><?= $math_score ?>/10</strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </a>

            <a href="pattern_training.php" class="fade-in" style="animation-delay: 0.1s;">
                <div class="category-card">
                    <img src="assets/img/810.jpg" alt="Pattern" class="category-card-image">
                    <div class="category-card-content">
                        <h3 class="category-card-title">🧩 Pattern</h3>
                        <p class="category-card-description">Test your ability to spot patterns and sequences with fun challenges.</p>
                        <?php if ($pattern_score !== null): ?>
                            <div class="category-card-status">
                                ✅ Completed: <strong><?= $pattern_score ?>/4</strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </a>

            <a href="deduction_training.php" class="fade-in" style="animation-delay: 0.2s;">
                <div class="category-card">
                    <img src="assets/img/q.jpg" alt="Deduction" class="category-card-image">
                    <div class="category-card-content">
                        <h3 class="category-card-title">🕵️‍♂️ Deduction</h3>
                        <p class="category-card-description">Improve your analytical thinking with challenging deduction puzzles.</p>
                        <?php if ($deduction_score !== null): ?>
                            <div class="category-card-status">
                                ✅ Completed: <strong><?= $deduction_score ?>/7</strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </a>

        </div>
    </div>

    <div id="levelUpModal" class="level-up-modal-overlay">
        <div class="level-up-modal-content">
            <h3>Congratulations! 🎉</h3>
            <p id="levelUpMessage"></p>
            <button id="closeLevelUpModal">Awesome!</button>
        </div>
    </div>

    <script>
        // Check if a level up occurred and show the modal
        <?php if (isset($_SESSION['level_up_to'])): ?>
            const levelUpModal = document.getElementById('levelUpModal');
            const levelUpMessage = document.getElementById('levelUpMessage');
            const closeLevelUpModalBtn = document.getElementById('closeLevelUpModal');

            // Get the new level from the session variable
            const newLevel = <?= json_encode($_SESSION['level_up_to']) ?>;
            levelUpMessage.textContent = `You've advanced to Level ${newLevel}! Keep up the great work!`;

            // Show the modal
            levelUpModal.classList.add('show');

            // Close the modal when the button is clicked
            closeLevelUpModalBtn.addEventListener('click', () => {
                levelUpModal.classList.remove('show');
            });

            // Clear the session variable after displaying the modal
            <?php unset($_SESSION['level_up_to']); ?>
        <?php endif; ?>
    </script>
</body>
</html>