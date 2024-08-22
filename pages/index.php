<!-- index.php -->
<?php 
require_once '../includes/config.php'; // Ensure this is at the very top

if (!isset($_SESSION['username'])) {
    // Redirect if not logged in
    header("Location: ../pages/login.php");
    exit();
}

// Fetch all approved questions with the number of replies
$sql = "SELECT q.*, 
               (SELECT COUNT(*) FROM answers a WHERE a.QuestionID = q.QuestionID) as reply_count 
        FROM questions q 
        WHERE q.isApproved = 'approved'";
$result = $conn->query($sql);


?>

<?php include '../includes/header.php'; ?>

<!-- link css -->
<link href="../css/header.css" rel="stylesheet">
<script src="../js/expandReply.js"></script>

<main class="main-content">
    <div class="index-title-con">
        <h1 class="index-title">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    </div>

    <div class="container">
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['QuestionTitle']); ?></h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($row['QuestionBody'])); ?></p>
                                <?php if ($row['questionImg']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($row['questionImg']); ?>" alt="Question Image" class="card-img-top">
                                <?php endif; ?>

                                <!-- Display the number of replies -->
                                <p><?php echo $row['reply_count']; ?> Replies</p>

                                <!-- Reply form -->
                                <form method="POST" action="../includes/postAnswers.php" class="mt-3">
                                    <input type="hidden" name="questionID" value="<?= htmlspecialchars($row['QuestionID']); ?>">
                                                        
                                    <!-- Reply Text Area -->
                                    <textarea class="form-control reply-field" name="answerContent" placeholder="reply..." rows="1"></textarea>
                                                        
                                    <!-- Post Answer Button -->
                                    <button type="submit" class="btn btn-primary mt-2" style="display:none;">Post Answer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No questions have been approved yet.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
