<?php include '../includes/header.php'; ?>

<!-- upload functionality -->
<?php
require_once '../includes/config.php';
require_once '../functionality/postActivity.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/login.php");
    exit();
}

// Assign the user ID to variable
$userID = $_SESSION['userID']; 
// Default value for new questions
$isApproved = 1; 

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
        $questionID = $stmt->insert_id; // Get the ID of the newly inserted question

        // Handle tags - get tag IDs
        if (isset($_POST['tags'])) {
            $tagNames = $_POST['tags'];
            
            // Prepare statements for tag insertion
            $tagStmt = $conn->prepare("INSERT INTO question_tags (QuestionID, TagID) VALUES (?, ?)");
            $tagStmt->bind_param("ii", $questionID, $tagID);

            foreach ($tagNames as $tagName) {
                // Get TagID from the tag name
                $tagQuery = $conn->prepare("SELECT TagID FROM tags WHERE TagName = ?");
                $tagQuery->bind_param("s", $tagName);
                $tagQuery->execute();
                $tagQuery->bind_result($tagID);
                $tagQuery->fetch();
                $tagQuery->close();

                if ($tagID) {
                    $tagStmt->execute(); // Insert into question_tags
                }
            }
            
            $tagStmt->close();
        }

        // logs the post being created for user activity feed
        logActivity($conn, $userID, 'Posted Question', ' posted a question titled: '.$qTitle );

        header("Location: ../pages/index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
    $conn->close();
}

// Fetch tags for the form
$tagSql = "SELECT TagID, TagName FROM tags";
$tagResult = $conn->query($tagSql);
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
                                <input type="text" class="form-control" id="questionTitle" name="questionTitle" placeholder="Question Title" required>
                            </div>
                            <div class="mt-4">
                                <textarea class="form-control question-text" id="questionDesc" name="questionDesc" placeholder="Describe your question" required></textarea>
                            </div>
                            <div class="mt-4">
                                <input class="form-control file-upload" type="file" id="questionPicture" name="picture">
                            </div>
                            <div class="mt-4">
                                <!-- Tags Selection -->
                                <label for="questionTags" class="form-label">Select Tags</label>
                                <select id="questionTags" name="tags[]" class="form-select" multiple required>
                                    <?php while($tag = $tagResult->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($tag['TagName']); ?>">
                                            <?php echo htmlspecialchars($tag['TagName']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mt-4">
                                <input type="submit" class="btn btn-post" name="submit" value="Upload">
                            </div>
                        </form>
                        <!-- form end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
