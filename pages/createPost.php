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



// Fetch all questions
$sql = "SELECT UserID, QuestionTitle, QuestionBody, questionImg, isApproved FROM questions";
$result = $conn->query($sql);
?>


<!-- link css -->
<link href="../css/createPost.css" rel="stylesheet">


<!-- html -->
<main>
    <div class="main-content">
        <!-- bootstrap cols/rows -->
        <div class="container">
            <div class="row">
                <div class="col-12 mt-3">
                    <!-- form title -->
                    <div class="new-post-con mt-5">
                        <h1 class="new-post-title mb-4">&#160Create a Post&#160</h1>
                        <!-- form start -->
                        <form class="new-post-form" action="createPost.php" method="post" enctype="multipart/form-data">
                            
                            <div class="mt-4">
                                <input type="text" class="form-control " id="questionTitle" name="questionTitle" placeholder="Question Title" required>
                            </div>
                            <div class="mt-4">
                                <textarea class="form-control question-text" id="questionDesc" name="questionDesc" placeholder="Describe your question" required></textarea>
                            </div>
                            <div class="mt-4">
                                <input class="form-control file-upload" type="file" id="questionPicture" name="picture">
                            </div>
                            <div class="mt-4">
                                <input type="submit" class="btn btn-post" name="submit" value="Upload">
                            </div>
                        </form>
                        <!-- form end -->
                    </div>
                </div>
            </div>

            <div class="row">
                    <?php
                    // UserID, QuestionTitle, QuestionBody, questionImg, isApproved
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<div class='movie-card'>";
                            echo "<h2>" . $row["QuestionTitle"] . "</h2>";
                            // echo "<p><strong>Genre:</strong> " . $row["genre"] . "</p>";
                            // echo "<p><strong>Release Date:</strong> " . $row["release_date"] . "</p>";
                            echo "<p><strong>Question Body:</strong> " . $row["QuestionBody"] . "</p>";
                            echo "<img src='../uploads/" . $row["questionImg"] . "' alt='" . $row["QuestionTitle"] . "'><br>";
                            echo "<button class='delete-btn' onclick='confirmDelete(" . $row["id"] . ")'>Delete</button>";
                            echo "</div>";
                        }
                    } else {
                        echo "No movies found.";
                    }
                    $conn->close();
                    ?>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
