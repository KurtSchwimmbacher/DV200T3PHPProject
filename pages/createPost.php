<!-- createPost.php -->
<?php include '../includes/header.php';?>

<!-- upload functionality -->
<?php
session_start(); // Start the session to access session variables
require '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/login.php");
    exit();
}

// Assign the user ID to variable
$userID = $_SESSION['userID']; 
// Default value for new questions
$isApproved = 0; 

// Handle form submission and question upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $qTitle = $_POST['questionTitle'];
    $qBody = $_POST['questionDesc'];

    // Handle file upload
    $qPic = NULL; // Default to NULL if no file is uploaded
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        
        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
            $qPic = basename($_FILES["picture"]["name"]); // Save the filename in the database
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Prepare an SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO questions (UserID, QuestionTitle, QuestionBody, questionImg, isApproved) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $userID, $qTitle, $qBody, $qPic, $isApproved);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Question uploaded successfully');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
    $conn->close();
}
?>


<!-- link css -->
<link href="../css/createPost.css" rel="stylesheet">


<!-- html -->
<main>
    <div class="main-content">
        <!-- bootstrap cols/rows -->
        <div class="container">
            <div class="row">
                <!-- used to space the form in the center -> empty col -->
                <div class="col-2"></div>

                <div class="col-8 mt-5">
                    <!-- form title -->
                    <div class="new-post-con mt-5">
                        <h1 class="new-post-title mb-4">Create a Post</h1>
                        <!-- form start -->
                        <form class="row g-3" action="createPost.php" method="post" enctype="multipart/form-data">
                            <div class="col-6 mt-4">
                                <input class="form-control file-upload" type="file" id="questionPicture" name="picture">
                            </div>
                            <div class="col-6 mt-4">
                                <input type="text" class="form-control " id="questionTitle" name="questionTitle" placeholder="Question Title" required>
                            </div>
                            <div class="col-6 mt-4">
                                <textarea class="form-control question-text" id="questionDesc" name="questionDesc" placeholder="Describe your question" required></textarea>
                            
                            <div class="col-6 mt-4">
                                <input type="submit" class="btn btn-post" name="submit" value="Upload">
                            </div>
                        </form>

                        <!-- form end -->

                    </div>
                </div>
                <!-- used to space the form in the center -> empty col -->
                <div class="col-2"></div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
