<?php
session_start();
include 'inc/connection.php';



if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');  // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name'];  // Get admin name from session

// Delete user if requested
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($con, "DELETE FROM logintb WHERE id = $id");
    header("Location: manage_users.php");
    exit();
}

// Fetch all users
$result = mysqli_query($con, "SELECT * FROM logintb"); 

// Function to get age group from DOB
function getAgeGroup($dob) {
    $age = date_diff(date_create($dob), date_create('today'))->y;
    if ($age >= 7 && $age <= 13) return "Level 1 (7-13)";
    if ($age >= 14 && $age <= 18) return "Level 2 (14-18)";
    if ($age >= 19) return "Level 3 (19+)";
    return "Unknown";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users - SmartMind</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
      margin-left: 0 px;
      padding: 30px;
    }

    h1 {
      color: var(--primary-color);
      font-size: 30px;
      margin-bottom: 20px;
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
    <h1>Manage Users</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>DOB</th>
          <th>Age Group</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($user = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['dob']) ?></td>
            <td><?= getAgeGroup($user['dob']) ?></td>
            <td class="actions">
              <a href="manage_users.php?delete=<?= $user['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash-alt"></i> Delete</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <a href="admin.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
  </div>
</body>
</html>
