<?php
session_start();
include 'inc/connection.php';

if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all users
$sql = "
    SELECT 
        u.id AS user_id,
        u.email,
        -- IQ Level Calculation (based on IQ percentage)
        (
            CASE
                WHEN iq.score_percent >= 85 THEN 3
                WHEN iq.score_percent >= 65 THEN 2
                ELSE 1
            END
        ) AS iq_level,
        -- Math score
        MAX(CASE WHEN s.subject = 'Math' THEN s.score END) AS math_score,
        MAX(CASE WHEN s.subject = 'Pattern' THEN s.score END) AS pattern_score,
        MAX(CASE WHEN s.subject = 'Deduction' THEN s.score END) AS deduction_score
    FROM logintb u
    LEFT JOIN (
        SELECT 
            ua.user_id,
            100 * SUM(CASE WHEN iq.correct_answer = ua.selected_option THEN 1 ELSE 0 END) / COUNT(*) AS score_percent
        FROM user_answers ua
        JOIN iqquestions iq ON ua.question_id = iq.id
        GROUP BY ua.user_id
    ) iq ON u.id = iq.user_id
    LEFT JOIN user_scores s ON u.id = s.user_id
    GROUP BY u.id, u.email, iq.score_percent
    ORDER BY u.email;
";

$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Training Results - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap Dark Theme -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: #fff; }
        .table-dark th, .table-dark td { vertical-align: middle; }
    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="mb-4 text-center">Training Results Overview</h2>

    <div class="table-responsive">
        <table class="table table-dark table-striped table-bordered">
            <thead class="table-primary text-dark">
                <tr>
                    <th>Email</th>
                    <th>IQ Level</th>
                    <th>Math Score</th>
                    <th>Pattern Score</th>
                    <th>Deduction Score</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): 
                $math = $row['math_score'] ?? 0;
                $pattern = $row['pattern_score'] ?? 0;
                $deduction = $row['deduction_score'] ?? 0;
                $level = $row['iq_level'] ?? 1;

                // Determine if finished current level (example condition: all 3 scores >= 70)
                $finished = ($math >= 7 && $pattern >= 3 && $deduction >= 3) ? '✅ Finished' : '⏳ In Progress';
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>Level <?= $level ?></td>
                    <td><?= $math ?></td>
                    <td><?= $pattern ?></td>
                    <td><?= $deduction ?></td>
                    <td class="<?= $finished === '✅ Finished' ? 'text-success' : 'text-warning' ?> fw-bold"><?= $finished ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
