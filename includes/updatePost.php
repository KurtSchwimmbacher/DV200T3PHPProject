<!-- updatePost.php -->

<?php 
include '../includes/header.php'; 
require_once '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/login.php");
    exit();
}

$userID = $_SESSION['userID']; 
$questionID = $_GET['id'];

// Fetch the question details
$sql = "SELECT * FROM questions WHERE QuestionID = ? AND UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $questionID, $userID);
$stmt->execute();
$result = $stmt->get_result();
$question = $result->fetch_assoc();

if (!$question) {
    echo "<script>alert('Question not found or you are not authorized to edit it.');</script>";
    header("Location: ../pages/feed.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $qTitle = $_POST['questionTitle'];
    $qBody = $_POST['questionDesc'];

    // Handle file upload
    $qPic = $question['questionImg']; // Use existing image by default
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        
        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
            $qPic = basename($_FILES["picture"]["name"]); // Save the filename in the database
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Update the question in the database
    $stmt = $conn->prepare("UPDATE questions SET QuestionTitle = ?, QuestionBody = ?, questionImg = ? WHERE QuestionID = ? AND UserID = ?");
    $stmt->bind_param("sssii", $qTitle, $qBody, $qPic, $questionID, $userID);

    if ($stmt->execute()) {
        echo "<script>alert('Question updated successfully');</script>";
        header("Location: ../pages/feed.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<main>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="new-post-con mt-5">
                        <h1 class="new-post-title mb-4">&#160Update Your Post&#160</h1>
                        <form class="new-post-form" action="updatePost.php?id=<?= $questionID ?>" method="post" enctype="multipart/form-data">
                            <div class="mt-4">
                                <input type="text" class="form-control " id="questionTitle" name="questionTitle" value="<?= htmlspecialchars($question['QuestionTitle']) ?>" required>
                            </div>
                            <div class="mt-4">
                                <textarea class="form-control question-text" id="questionDesc" name="questionDesc" required><?= htmlspecialchars($question['QuestionBody']) ?></textarea>
                            </div>
                            <div class="mt-4">
                                <input class="form-control file-upload" type="file" id="questionPicture" name="picture">
                            </div>
                            <div class="mt-4">
                                <input type="submit" class="btn btn-post" name="submit" value="Update">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
