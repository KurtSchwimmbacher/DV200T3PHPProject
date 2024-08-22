<?php
require_once '../includes/config.php';

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

    if ($stmt->execute()) {
        header("Location: ../pages/index.php"); // Redirect back to the home page after posting the answer
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
