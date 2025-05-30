<?php
session_start();

include "inc/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['txtEmail']);
    $password = trim($_POST['txtPassword']);

    // Fetch user from database
    $sql = "SELECT id, name, email, password FROM logintb WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_name'] = $row['name'];
            
            header("Location: iqquestions.php");
            exit;
        } else {
            header("Location: login.php?error=Invalid password");
            exit;
        }
    } else {
        header("Location: login.php?error=No user found");
        exit;
    }

    $stmt->close();
}
?>
