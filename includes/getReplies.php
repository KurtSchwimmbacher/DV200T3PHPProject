<?php
require_once '../includes/config.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['questionID'])) {
    $questionID = intval($_GET['questionID']);
    
    $stmt = $conn->prepare("SELECT * FROM answers WHERE QuestionID = ?");
    if ($stmt === false) {
        die(json_encode(['error' => 'Database prepare error']));
    }

    $stmt->bind_param("i", $questionID);
    $stmt->execute();
    $result = $stmt->get_result();

    $replies = [];
    while ($row = $result->fetch_assoc()) {
        $replies[] = $row;
    }

    echo json_encode($replies);

    $stmt->close();
}

$conn->close();
?>
