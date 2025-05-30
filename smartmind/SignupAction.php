<?php
session_start();
include "inc/connection.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['txtname']);
    $email = trim($_POST['txtEmail']);
    $password = trim($_POST['txtPassword']);
    $dob = trim($_POST['txtDob']); 

    
    $check_email = "SELECT * FROM logintb WHERE email = ?";
    $stmt = $con->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
       
        header("Location: signup.php?error=Email already registered");
        exit;
    }

   
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $sql = "INSERT INTO logintb (name, email, password, dob) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $dob);

    if ($stmt->execute()) {
      
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['users'] = $email; 
        header("Location: iqquestions.php"); 
        exit;
    } else {
        header("Location: signup.php?error=Something went wrong");
        exit;
    }

    $stmt->close();
}
?>
