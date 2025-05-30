<?php 
session_start();
include 'inc/connection.php';



if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');  // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name'];  // Get admin name from session

// Fetch all math questions
$query = "SELECT * FROM math_questions ORDER BY ID";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Math Questions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white font-sans">

    <div class="max-w-6xl mx-auto p-6 mt-10 bg-gray-900 rounded-xl shadow-2xl">
        <h2 class="text-3xl font-bold text-center text-blue-400 mb-6">Manage Math Questions</h2>

        <div class="flex justify-between mb-6">
            <a href="add_logic.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold transition duration-300">
                ➕ Add New Question
            </a>
            <a href="admin.php" class="bg-gray-800 hover:bg-gray-700 text-white px-5 py-2 rounded-lg font-semibold transition duration-300">
                ← Back to Admin Dashboard
            </a>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-700">
            <table class="min-w-full text-sm text-center text-gray-300">
                <thead class="bg-blue-800 text-white uppercase text-sm">
                    <tr>
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">Question</th>
                        <th class="py-3 px-4">Option A</th>
                        <th class="py-3 px-4">Option B</th>
                        <th class="py-3 px-4">Option C</th>
                        <th class="py-3 px-4">Option D</th>
                        <th class="py-3 px-4">Answer</th>
                        <th class="py-3 px-4">Age-Level</th>
                        <th class="py-3 px-4">IQ-Level</th>
                        <th class="py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class="hover:bg-gray-700 transition">
                        <td class="py-3 px-4"><?= $row['id'] ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($row['question_text']) ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($row['option_a']) ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($row['option_b']) ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($row['option_c']) ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($row['option_d']) ?></td>
                        <td class="py-3 px-4 font-bold text-green-400"><?= $row['correct_option'] ?></td>
                        <td class="py-3 px-4">Age <?= $row['age_level'] ?></td>
                        <td class="py-3 px-4">IQ <?= $row['iq_level'] ?></td>
                        <td class="py-3 px-4 space-x-2">
                            <a href="edit_math.php?id=<?= $row['id'] ?>" class="bg-green-600 hover:bg-green-700 px-3 py-1 rounded-md">Edit</a>
                            <a href="delete_math.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded-md">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if (mysqli_num_rows($result) == 0): ?>
                    <tr>
                        <td colspan="10" class="py-6 text-center text-gray-400">No questions found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
