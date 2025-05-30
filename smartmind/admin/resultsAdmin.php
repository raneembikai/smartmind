<?php
session_start();
include 'inc/connection.php';

if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');
    exit();
}

$admin_name = $_SESSION['admin_name'];
$user_filter = '';
if (isset($_GET['user'])) {
    $user_filter = mysqli_real_escape_string($con, $_GET['user']);
}

$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

$sql = "
    SELECT 
        logintb.id AS user_id,
        logintb.name AS user_name,
        logintb.email,
        SUM(CASE WHEN iqquestions.correct_answer = user_answers.selected_option THEN 1 ELSE 0 END) AS score,
        COUNT(user_answers.id) AS total_questions
    FROM user_answers
    JOIN logintb ON user_answers.user_id = logintb.id
    JOIN iqquestions ON user_answers.question_id = iqquestions.id
    WHERE logintb.name LIKE ? OR logintb.email LIKE ?
    GROUP BY logintb.id
    ORDER BY logintb.name
    LIMIT ? OFFSET ?
";

$stmt = mysqli_prepare($con, $sql);
$search_term = "%$user_filter%";
mysqli_stmt_bind_param($stmt, 'ssii', $search_term, $search_term, $per_page, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$total_sql = "
    SELECT COUNT(DISTINCT logintb.id) AS total_users
    FROM user_answers
    JOIN logintb ON user_answers.user_id = logintb.id
    WHERE logintb.name LIKE ? OR logintb.email LIKE ?
";
$total_stmt = mysqli_prepare($con, $total_sql);
mysqli_stmt_bind_param($total_stmt, 'ss', $search_term, $search_term);
mysqli_stmt_execute($total_stmt);
$total_result = mysqli_stmt_get_result($total_stmt);
$total_users = mysqli_fetch_assoc($total_result)['total_users'];
$total_pages = ceil($total_users / $per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - IQ Test Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 Dark Theme -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }
        .table thead {
            background-color: #1f1f1f;
        }
        .table tbody tr:hover {
            background-color: #2a2a2a;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="text-center mb-4">
        <h1 class="fw-bold">Admin Dashboard - IQ Test Results</h1>
    </div>

    <!-- Search -->
    <form method="GET" class="d-flex mb-3">
        <input type="text" name="user" placeholder="Search by name or email" class="form-control me-2 bg-dark text-white" value="<?= htmlspecialchars($user_filter) ?>">
        <button class="btn btn-primary">Search</button>
    </form>

    <!-- Back Button -->
    <a href="admin.php" class="btn btn-outline-light mb-3">← Back to Admin Dashboard</a>

    <!-- Results Table -->
    <div class="table-responsive">
        <table class="table table-dark table-bordered table-hover align-middle text-center">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Total Questions</th>
                    <th>Score</th>
                    <th>Percentage</th>
                    <th>Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)):
                    $score = $row['score'];
                    $total = $row['total_questions'];
                    $percentage = ($total > 0) ? ($score / $total) * 100 : 0;

                    if ($percentage >= 85) {
                        $level = 3;
                    } elseif ($percentage >= 65) {
                        $level = 2;
                    } elseif ($percentage >= 50) {
                        $level = 1;
                    } else {
                        $level = 1;
                    }
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= $total ?></td>
                        <td class="<?= $score == $total ? 'text-success fw-bold' : 'text-danger' ?>"><?= $score ?></td>
                        <td><?= round($percentage, 2) ?>%</td>
                        <td class="text-info">Level <?= $level ?></td>
                        <td>
                            <form method="POST" action="deleteUser_result.php" onsubmit="return confirm('Are you sure you want to delete this result?');">
                                <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-muted">No results found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav class="mt-4 d-flex justify-content-center">
        <ul class="pagination pagination-dark">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&user=<?= urlencode($user_filter) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

</body>
</html>
