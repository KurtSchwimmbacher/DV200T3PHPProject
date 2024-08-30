<!-- singleQuestion.php -->
<?php 
require_once '../includes/config.php'; // Ensure this is at the very top

if (!isset($_SESSION['username'])) {
    // Redirect if not logged in
    header("Location: ../pages/login.php");
    exit();
}

// Get the questionID from the URL
if (!isset($_GET['questionID'])) {
    // Redirect to index if no question ID is provided
    header("Location: ../pages/index.php");
    exit();
}

$questionID = intval($_GET['questionID']);

// Fetch the specific question
$sql = "SELECT q.*,
                u.username,
               (SELECT SUM(v.voteValue) FROM votes v WHERE v.AnswerID IN (SELECT AnswerID FROM answers WHERE QuestionID = q.QuestionID)) as totalVotes
        FROM questions q 
        JOIN users u on q.UserID = u.id
        WHERE q.QuestionID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $questionID);
$stmt->execute();
$questionResult = $stmt->get_result();
$question = $questionResult->fetch_assoc();

// Fetch replies for the specific question
$sql_replies = "SELECT a.*, u.username, COALESCE(SUM(v.voteValue), 0) as totalVotes
                FROM answers a
                LEFT JOIN users u ON a.UserID = u.id
                LEFT JOIN votes v ON a.AnswerID = v.AnswerID
                WHERE a.QuestionID = ?
                GROUP BY a.AnswerID
                ORDER BY a.postedTime ASC";
$stmt_replies = $conn->prepare($sql_replies);
$stmt_replies->bind_param("i", $questionID);
$stmt_replies->execute();
$repliesResult = $stmt_replies->get_result();
?>

<?php include '../includes/header.php'; ?>

<!-- link css -->
<link href="../css/header.css" rel="stylesheet">

<!-- link js -->
<script src="../js/vote.js"></script>

<main class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-body">
                        <div class="filtered-bg"></div>
                        <!-- display username -->
                        <p class="card-text">
                            <small class="text-muted"><?php echo htmlspecialchars($question['username']) ?></small>
                        </p>
                        <h3 class="card-title"><?php echo htmlspecialchars($question['QuestionTitle']); ?></h3>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($question['QuestionBody'])); ?></p>
                        <?php if ($question['questionImg']): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($question['questionImg']); ?>" alt="Question Image" class="card-img-top">
                        <?php endif; ?>
                        
                        <!-- Like/Dislike buttons -->
                        <button class="btn vote-btn" data-action="like" data-question-id="<?php echo htmlspecialchars($question['QuestionID']); ?>">
                            <i class="bi bi-arrow-up"></i> <!-- Added closing tag -->
                        </button>
                        <span class="vote-count" id="vote-count-<?php echo htmlspecialchars($question['QuestionID']); ?>">
                            <?php echo $question['totalVotes']; ?>
                        </span>
                        <button class="btn vote-btn" data-action="dislike" data-question-id="<?php echo htmlspecialchars($question['QuestionID']); ?>">
                            <i class="bi bi-arrow-down"></i> <!-- Added closing tag -->
                        </button>


                        <hr>
                        
                        <h4>Replies</h4>
                        <?php if ($repliesResult->num_rows > 0): ?>
                            <?php while($reply = $repliesResult->fetch_assoc()): ?>
                                <div class="reply">
                                    <p><strong><?php echo htmlspecialchars($reply['username']); ?>:</strong> <?php echo nl2br(htmlspecialchars($reply['AnswerContent'])); ?></p>
                                    <p>Posted on: <?php echo htmlspecialchars($reply['postedTime']); ?></p>

                                    <hr>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No replies yet. Be the first to reply!</p>
                        <?php endif; ?>
                        
                        <!-- Reply form -->
                        <form method="POST" action="../includes/postAnswers.php" class="mt-3">
                            <input type="hidden" name="questionID" value="<?= htmlspecialchars($question['QuestionID']); ?>">
                                                    
                            <!-- Reply Text Area -->
                            <textarea class="form-control reply-field" name="answerContent" placeholder="Write your reply..." rows="3" required></textarea>
                                                    
                            <!-- Post Answer Button -->
                            <button type="submit" class="btn btn-post-reply mt-2">Post Reply</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/filters.php'; ?>
<?php include '../includes/footer.php'; ?>
