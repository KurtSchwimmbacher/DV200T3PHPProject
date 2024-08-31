<?php
require_once '../includes/config.php';
require_once '../functionality/postActivity.php';

if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $questionID = $_POST['questionID'];
    $userID = $_SESSION['userID'];
    $answerContent = $_POST['answerContent'];
    $answerImg = NULL; // Placeholder for optional image upload

    // Handle image upload (if applicable)
    if (isset($_FILES['answerPicture']) && $_FILES['answerPicture']['error'] == 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["answerPicture"]["name"]);
        
        if (move_uploaded_file($_FILES["answerPicture"]["tmp_name"], $target_file)) {
            $answerImg = basename($_FILES["answerPicture"]["name"]);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $stmt = $conn->prepare("INSERT INTO answers (QuestionID, UserID, answerContent, answerImg, postedTime) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiss", $questionID, $userID, $answerContent, $answerImg);

    // Fetch the question details for logging
    $fetchSql = "SELECT UserID, QuestionTitle FROM questions WHERE QuestionID = ?";
    $fetchStmt = $conn->prepare($fetchSql);
    $fetchStmt->bind_param("i", $questionID);
    $fetchStmt->execute();
    $questionDetails = $fetchStmt->get_result()->fetch_assoc();
    $fetchStmt->close();

    // Extract UserID and QuestionTitle from the fetched details
    $questionUserID = $questionDetails['UserID'];
    $questionTitle = $questionDetails['QuestionTitle'];

    // Fetch the username from the users table using the UserID
    $userFetchSql = "SELECT username FROM users WHERE id = ?";
    $userFetchStmt = $conn->prepare($userFetchSql);
    $userFetchStmt->bind_param("i", $userID);
    $userFetchStmt->execute();
    $userDetails = $userFetchStmt->get_result()->fetch_assoc();
    $userFetchStmt->close();

    // Extract the username
    $username = $userDetails['username'];


    if ($stmt->execute()) {
      // logs the post being replied to for user activity feed
      logActivity($conn, $questionUserID , 'Reply', $username . ' replied to your question titled: '.$questionTitle );  
      // Redirect back to the singleQuestion page after posting the answer
      header("Location: ../pages/singleQuestion.php"); 
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
