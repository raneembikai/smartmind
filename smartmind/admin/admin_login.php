<?php
session_start();
include('inc/connection.php');  // Include your DB connection file

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email and password match the admin's credentials in the database
    $query = "SELECT * FROM admin WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) == 1) {
        // Fetch the admin details
        $admin = mysqli_fetch_assoc($result);
        
        // Set session variables for the admin
        $_SESSION['admin_email'] = $email;
        $_SESSION['admin_name'] = $admin['name'];  // Store the admin's name in session
        
        header('Location: admin.php');  // Redirect to admin dashboard
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Additional styling for the dark theme */
        body {
            background-color: #121212;
            color: #eaeaea;
        }
        .login-card {
            background-color: #1f1f1f;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 600;
            color: #eaeaea;
        }
        .form-input {
            background-color: #333;
            border: 1px solid #444;
            color: #eaeaea;
        }
        .btn-login {
            background-color:rgb(47, 55, 195);
            color: white;
            font-size: 16px;
            padding: 12px 0;
            border-radius: 5px;
            width: 100%;
        }
        .btn-login:hover {
            background-color: #45a049;
        }
        .error-message {
            color: #f44336;
            font-size: 14px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100">

    <div class="login-card p-4 w-100" style="max-width: 400px;">
        <h2 class="text-center text-light mb-4">Admin Login</h2>

        <?php
        if (isset($error_message)) {
            echo '<p class="error-message text-center mb-4">' . $error_message . '</p>';
        }
        ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control form-input" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control form-input" required>
            </div>
            <button type="submit" name="login" class="btn-login">Login</button>
        </form>

        <p class="text-center text-light mt-3">
            Not an admin? <a href="index.php" class="text-primary">Go to Home</a>
        </p>
    </div>

    <!-- Bootstrap 5 JS (optional for any interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
