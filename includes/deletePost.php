<!-- deletePost.php -->

<?php
require_once '../includes/config.php';

if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/login.php");
    exit();
}

$userID = $_SESSION['userID'];
$questionID = $_GET['id'];

// Delete the question from the database
$sql = "DELETE FROM questions WHERE QuestionID = ? AND UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $questionID, $userID);


if ($stmt->execute()) {
    echo "<script>alert('Question deleted successfully');</script>";
    header("Location: ../pages/feed.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
