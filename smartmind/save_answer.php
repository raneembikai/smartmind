<?php
session_start();
include("inc/connection.php");

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $_SESSION['user_id'] ?? null; 
$question_id = $data['question_id'] ?? null;
$selected_option = $data['selected_option'] ?? null;

if (!$user_id || !$question_id || !$selected_option) {
    http_response_code(400);
    echo "Invalid input";
    exit();
}

$stmt = $con->prepare("INSERT INTO user_answers (user_id, question_id, selected_option) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $question_id, $selected_option);
$stmt->execute();
$stmt->close();

echo "Answer saved.";
?>