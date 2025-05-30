<?php
session_start();
include 'inc/connection.php';



if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');  // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name'];  // Get admin name from session

// Fetch all IQ questions
$query = "SELECT * FROM iqquestions ORDER BY level, id";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage IQ Questions</title>
    <style>
        :root {
            --primary-color: #0066cc;
            --secondary-color: #1c1c1c;
            --hover-color: #005bb5;
            --table-header-bg: #333;
            --table-bg: #212121;
            --text-light: #ddd;
            --text-dark: #f1f1f1;
            --delete-color: #d9534f;
            --button-hover-color: #5bc0de;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-light);
        }

        .container {
            margin-left: 0;
            padding: 30px;
        }

        h2 {
            color: var(--primary-color);
            font-size: 30px;
            margin-bottom: 20px;
            text-align: center;
        }

        a.btn {
            display: inline-block;
            margin: 10px 10px 20px 0;
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        a.btn:hover {
            background-color: var(--button-hover-color);
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--table-bg);
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        th, td {
            padding: 16px;
            text-align: center;
            border-bottom: 1px solid #444;
        }

        th {
            background-color: var(--table-header-bg);
            color: var(--text-light);
            font-size: 15px;
            text-transform: uppercase;
        }

        td {
            color: var(--text-light);
            font-size: 14px;
        }

        tr:hover {
            background-color: #2a2a2a;
        }

        .action-btns a {
            margin: 0 5px;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .edit-btn {
            background-color: #28a745;
            color: white;
        }

        .delete-btn {
            background-color: var(--delete-color);
            color: white;
        }

        .edit-btn:hover, .delete-btn:hover {
            background-color: var(--button-hover-color);
            color: white;
        }

        @media (max-width: 768px) {
            table, th, td {
                font-size: 13px;
                padding: 10px;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage IQ Questions</h2>

    <a href="add_question.php" class="btn">Add New Question</a>
    <a href="admin.php" class="btn">⬅ Back to Admin Dashboard</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Option A</th>
            <th>Option B</th>
            <th>Option C</th>
            <th>Option D</th>
            <th>Correct</th>
            <th>Level</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['question']) ?></td>
            <td><?= htmlspecialchars($row['option_a']) ?></td>
            <td><?= htmlspecialchars($row['option_b']) ?></td>
            <td><?= htmlspecialchars($row['option_c']) ?></td>
            <td><?= htmlspecialchars($row['option_d']) ?></td>
            <td><?= $row['correct_answer'] ?></td>
            <td>Level <?= $row['level'] ?></td>
            <td class="action-btns">
                <a href="edit_question.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                <a href="delete_question.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this question?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
