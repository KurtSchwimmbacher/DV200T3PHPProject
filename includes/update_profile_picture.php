<?php
require_once '../includes/config.php';

// Check if a file was uploaded and the user is logged in
if (isset($_FILES['profile_picture']) && isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $file = $_FILES['profile_picture'];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die('An error occurred while uploading the file.');
    }

    // Validate the uploaded file (e.g., size, type)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        die('Invalid file type. Only JPG, PNG, and GIF are allowed.');
    }

    // Generate a new file name and move the file to the server
    $uploadDir = '../uploads/profile_pictures/';
    $fileName = uniqid() . '-' . basename($file['name']);
    $filePath = $uploadDir . $fileName;
    


    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Update the profile picture path in the database
        $updateQuery = "UPDATE users SET profile_picture = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $profilePicturePath = 'uploads/profile_pictures/' . $fileName; // Relative path for display
        $updateStmt->bind_param("si", $profilePicturePath, $userID);

        if ($updateStmt->execute()) {
            echo "Profile picture updated successfully.";
            header("Location: ../pages/userActivity.php");
        } else {
            echo "Error updating profile picture: " . $conn->error;
        }

        $updateStmt->close();
    } else {
        echo 'Failed to move the uploaded file.';
    }
} else {
    echo 'No file uploaded or user not logged in.';
}

$conn->close();
?>
