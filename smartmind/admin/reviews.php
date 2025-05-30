<?php
session_start();
include 'inc/connection.php';



if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');  // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name'];  // Get admin name from session

// Fetch contact messages
$query = "SELECT name, email, message, created_at FROM contacts ORDER BY created_at DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - User Reviews</title>
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

        h1 {
            color: var(--primary-color);
            font-size: 30px;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--table-bg);
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            margin-top: 30px;
        }

        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #444;
        }

        th {
            background-color: var(--table-header-bg);
            color: var(--text-light);
            font-size: 16px;
            text-transform: uppercase;
        }

        td {
            color: var(--text-light);
            font-size: 14px;
        }

        tr:hover {
            background-color: var(--secondary-color);
        }

        .actions a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: bold;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .actions a.delete {
            color: var(--delete-color);
        }

        .actions a:hover {
            background-color: var(--button-hover-color);
            color: white;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: var(--primary-color);
            font-size: 16px;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .back-link:hover {
            background-color: var(--button-hover-color);
            color: white;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                margin-left: 0;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Reviews</h1>

        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                            <td><?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if (mysqli_num_rows($result) === 0): ?>
                        <tr>
                            <td colspan="4" class="text-center">No reviews found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <a href="admin.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>
</html>
