<?php 
require_once '../includes/config.php';

function logActivity($conn, $userID, $activityType, $details) {
    $sql = "INSERT INTO user_activities (UserID, ActivityType, ActivityDetails) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $userID, $activityType, $details);
    $stmt->execute();
}
?>