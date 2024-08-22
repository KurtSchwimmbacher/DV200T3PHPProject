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
               (SELECT COUNT(*) FROM answers a WHERE a.QuestionID = q.QuestionID) as reply_count,
               COALESCE((SELECT SUM(v.voteValue) FROM votes v WHERE v.AnswerID IN (SELECT AnswerID FROM answers WHERE QuestionID = q.QuestionID)), 0) as totalVotes
        FROM questions q 
        WHERE q.isApproved = 'approved'";
$result = $conn->query($sql);


?>

<?php include '../includes/header.php'; ?>

<!-- link css -->
<link href="../css/header.css" rel="stylesheet">

<!-- link js -->
<script src="../js/expandReply.js"></script>
<script src="../js/loadReplies.js"></script>
<script src="../js/vote.js"></script>

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

                                <!-- Display the number of replies with a clickable element -->
                                <p>
                                    <span class="reply-count" data-question-id="<?php echo htmlspecialchars($row['QuestionID']); ?>">
                                        <?php echo $row['reply_count']; ?> Replies
                                    </span>
                                </p>

                                <!-- Reply form -->
                                <form method="POST" action="../includes/postAnswers.php" class="mt-3">
                                    <input type="hidden" name="questionID" value="<?= htmlspecialchars($row['QuestionID']); ?>">
                                                        
                                    <!-- Reply Text Area -->
                                    <textarea class="form-control reply-field" name="answerContent" placeholder="reply..." rows="1"></textarea>
                                                        
                                    <!-- Post Answer Button -->
                                    <button type="submit" class="btn btn-primary mt-2" style="display:none;">Post Answer</button>
                                </form>

                                <!-- Like/Dislike buttons -->
                                <button class="btn btn-success vote-btn" data-action="like" data-question-id="<?php echo htmlspecialchars($row['QuestionID']); ?>">Like</button>
                                <span class="vote-count" id="vote-count-<?php echo htmlspecialchars($row['QuestionID']); ?>"><?php echo $row['totalVotes']; ?></span>
                                <button class="btn btn-danger vote-btn" data-action="dislike" data-question-id="<?php echo htmlspecialchars($row['QuestionID']); ?>">Dislike</button>
                                
                                <!-- Section for displaying replies -->
                                <div class="replies" id="replies-<?php echo htmlspecialchars($row['QuestionID']); ?>" style="display:none;">
                                    <!-- Replies will be dynamically loaded here -->
                                </div>
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
