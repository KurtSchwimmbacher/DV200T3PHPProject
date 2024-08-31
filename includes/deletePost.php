<!-- deletePost.php -->

<?php
require_once '../includes/config.php';
require_once '../functionality/postActivity.php';

if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/login.php");
    exit();
}

$userID = $_SESSION['userID'];
$questionID = $_GET['id'];

// Fetch the post information before deleting it
$sql = "SELECT * FROM questions WHERE QuestionID = ? AND UserID = ?";
$GetStmt = $conn->prepare($sql);
$GetStmt->bind_param("ii", $questionID, $userID);
$GetStmt->execute();
$result = $GetStmt->get_result();

// Check if the post exists and fetch the data
if ($result->num_rows > 0) {
    // Fetch the row as an associative array
    $post = $result->fetch_assoc(); 
    $questionTitle = $post['QuestionTitle']; 
} else {
    echo "Post not found or you do not have permission to delete this post.";
    exit();
}

$GetStmt->close();

// Delete the question from the database
$sql = "DELETE FROM questions WHERE QuestionID = ? AND UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $questionID, $userID);

if ($stmt->execute()) {
    // Log the activity of the post being deleted
    logActivity($conn, $userID, 'Post Deleted', 'deleted post titled: ' . htmlspecialchars($questionTitle));
    header("Location: ../pages/feed.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
