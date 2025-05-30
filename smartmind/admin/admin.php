<?php
session_start();
include("../inc/connection.php");


if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');  // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name'];  // Get admin name from session


$userCountQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM logintb");
$userCountRow = mysqli_fetch_assoc($userCountQuery);
$totalUsers = $userCountRow['total'];

$data = [];
$months = [];

$query = "SELECT MONTH(created_at) as month, COUNT(*) as count FROM logintb GROUP BY MONTH(created_at)";
$result = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $months[] = date("F", mktime(0, 0, 0, $row['month'], 10)); // Month name
    $data[] = $row['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SmartMind Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    :root {
      --primary: #007BFF;
      --secondary: #0d1117;
      --accent: #1f6feb;
      --bg-light: #f1f3f5;
      --text-light: #d0d3d6;
      --text-dark: #ffffff;
      --card-bg: #161b22;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--secondary);
      color: var(--text-dark);
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      background-color: var(--card-bg);
      position: fixed;
      display: flex;
      flex-direction: column;
      padding: 25px 20px;
      overflow-y: auto;
    }

    .sidebar h2 {
      text-align: center;
      color: var(--primary);
      margin-bottom: 40px;
      font-size: 22px;
    }

    .sidebar a {
      color: var(--text-light);
      text-decoration: none;
      padding: 14px 18px;
      border-radius: 6px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      transition: 0.3s;
      font-size: 15px;
    }

    .sidebar a i {
      margin-right: 12px;
    }

    .sidebar a:hover {
      background-color: var(--primary);
      color: #fff;
    }

    .main {
      margin-left: 250px;
      padding: 30px;
    }

    .header {
      background-color: var(--card-bg);
      padding: 20px 30px;
      border-radius: 10px;
      margin-bottom: 25px;
      box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header h1 {
      color: var(--primary);
      font-size: 24px;
    }

    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }

    .card {
      background-color: var(--card-bg);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 123, 255, 0.1);
      text-align: center;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card i {
      font-size: 28px;
      color: var(--primary);
      margin-bottom: 10px;
    }

    .card h3 {
      margin-bottom: 6px;
      color: var(--text-dark);
    }

    .card p {
      font-size: 16px;
      color: var(--text-light);
    }

    .chart-card {
      background-color: var(--card-bg);
      margin-top: 30px;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0, 123, 255, 0.1);
      max-width: 100%;
    }

    .chart-container {
      position: relative;
      height: 300px;
      width: 100%;
    }

    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }
      .main {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>🧠 SmartMind</h2>
    <a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="manage_users.php"><i class="fas fa-users-cog"></i> Manage Users</a>
    <a href="reviews.php"><i class="fas fa-star"></i> Users Reviews</a>
    <a href="manage_iqquestions.php"><i class="fas fa-brain"></i> IQ Tests</a>
    <a href="add_question.php"><i class="fas fa-plus-circle"></i> Add Questions</a>
    <a href="resultsAdmin.php"><i class="fas fa-chart-bar"></i> User Results IQ </a>
    <a href="training_logic.php"><i class="fas fa-chart-bar"></i> User Result for training</a>
    <a href="manage_math.php"><i class="fas fa-calculator"></i> Math</a>
    <a href="manage_pattern.php"><i class="fas fa-shapes"></i> Pattern</a>
    <a href="manage_deduction.php"><i class="fas fa-balance-scale"></i> Deduction</a>
    <a href="add_logic.php"><i class="fas fa-plus-circle"></i> Add Math</a>
    <a href="add_deduction.php"><i class="fas fa-plus-circle"></i> Add Deduction</a>
    <a href="add_pattern.php"><i class="fas fa-plus-circle"></i> Add Pattern</a>
    <a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main">
    <div class="header">
      <h1>Welcome to SmartMind Admin 👩‍💼</h1>
    </div>

    <div class="dashboard">
      <div class="card">
        <i class="fas fa-users"></i>
        <h3>Total Users</h3>
        <p><?= $totalUsers ?></p>
      </div>

      <div class="card">
        <i class="fas fa-brain"></i>
        <h3>IQ Tests</h3>
        <p>Level 1–3</p>
      </div>

      <div class="card">
        <i class="fas fa-question-circle"></i>
        <h3>Total Questions</h3>
        <p>215 Questions</p>
      </div>

      <div class="card">
        <i class="fas fa-chart-line"></i>
        <h3>Performance Insights</h3>
        <p>Live Tracking</p>
      </div>
    </div>

    <div class="chart-card">
      <h3 style="margin-bottom: 15px;">User Registrations Per Month</h3>
      <div class="chart-container">
        <canvas id="userChart"></canvas>
      </div>
    </div>
  </div>

<script>
const ctx = document.getElementById('userChart').getContext('2d');

// Gradient background
const gradient = ctx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(0, 123, 255, 0.4)');
gradient.addColorStop(1, 'rgba(0, 123, 255, 0)');

const userChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'Monthly Users',
            data: <?= json_encode($data) ?>,
            fill: true,
            backgroundColor: gradient,
            borderColor: '#1f6feb',
            tension: 0.4,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#1f6feb',
            pointHoverBackgroundColor: '#1f6feb',
            pointHoverBorderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#ffffff',
                    font: {
                        size: 14
                    }
                }
            },
            tooltip: {
                backgroundColor: '#1f6feb',
                titleColor: '#ffffff',
                bodyColor: '#ffffff'
            }
        },
        scales: {
            x: {
                ticks: { color: '#ffffff' },
                grid: { color: 'rgba(255,255,255,0.05)' }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#ffffff',
                    stepSize: 1
                },
                grid: { color: 'rgba(255,255,255,0.05)' }
            }
        }
    }
});
</script>

</body>
</html>
