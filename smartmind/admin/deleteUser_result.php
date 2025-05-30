<?php
// Include database connection
session_start();
include 'inc/connection.php';



if (!isset($_SESSION['admin_email'])) {
    header('Location: admin_login.php');  // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name'];  // Get admin name from session

// Check if the user is logged in or has the necessary permissions (you can add your session checks here)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the POST request
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

    if ($user_id > 0) {
        // Start a transaction to ensure both deletions are handled together (if necessary)
        mysqli_begin_transaction($con);

        try {
            // Delete user answers from the user_answers table
            $delete_answers_sql = "DELETE FROM user_answers WHERE user_id = ?";
            $stmt = mysqli_prepare($con, $delete_answers_sql);
            mysqli_stmt_bind_param($stmt, 'i', $user_id);
            $stmt_execute = mysqli_stmt_execute($stmt);

            // If no errors occurred, commit the transaction
            if ($stmt_execute) {
                // Commit the transaction
                mysqli_commit($con);

                // Redirect back to the results page after successful deletion
                header('Location: resultsAdmin.php');
                exit();
            } else {
                throw new Exception('Error executing the query to delete user answers');
            }
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            mysqli_rollback($con);
            die('Error deleting user answers: ' . $e->getMessage());
        }
    } else {
        die('Invalid user ID');
    }
} else {
    die('Invalid request method');
}
?>
