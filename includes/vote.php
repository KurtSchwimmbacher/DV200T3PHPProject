<?php
require_once '../includes/config.php';

if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_SESSION['userID'];
    $questionID = intval($_POST['questionID']);
    $action = $_POST['action']; // 'like' or 'dislike'
    $voteValue = ($action === 'like') ? 1 : -1;

    // Check if the user has already voted on this question
    $stmt = $conn->prepare("SELECT * FROM votes WHERE UserID = ? AND AnswerID IN (SELECT AnswerID FROM answers WHERE QuestionID = ?)");
    $stmt->bind_param("ii", $userID, $questionID);
    $stmt->execute();
    $existingVote = $stmt->get_result()->fetch_assoc();
    
    if ($existingVote) {
        // Update the existing vote
        $stmt = $conn->prepare("UPDATE votes SET voteValue = ? WHERE UserID = ? AND AnswerID IN (SELECT AnswerID FROM answers WHERE QuestionID = ?)");
        $stmt->bind_param("iii", $voteValue, $userID, $questionID);
    } else {
        // Insert a new vote
        $stmt = $conn->prepare("INSERT INTO votes (UserID, AnswerID, voteValue) SELECT ?, AnswerID, ? FROM answers WHERE QuestionID = ?");
        $stmt->bind_param("iii", $userID, $voteValue, $questionID);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
