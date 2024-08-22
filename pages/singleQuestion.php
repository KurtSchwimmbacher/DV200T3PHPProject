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
               (SELECT SUM(v.voteValue) FROM votes v WHERE v.AnswerID IN (SELECT AnswerID FROM answers WHERE QuestionID = q.QuestionID)) as totalVotes
        FROM questions q 
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
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($question['QuestionTitle']); ?></h3>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($question['QuestionBody'])); ?></p>
                        <?php if ($question['questionImg']): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($question['questionImg']); ?>" alt="Question Image" class="card-img-top">
                        <?php endif; ?>
                        
                        <!-- Like/Dislike buttons -->
                        <button class="btn btn-success vote-btn" data-action="like" data-question-id="<?php echo htmlspecialchars($question['QuestionID']); ?>">Like</button>
                        <span class="vote-count" id="vote-count-<?php echo htmlspecialchars($question['QuestionID']); ?>"><?php echo $question['totalVotes']; ?></span>
                        <button class="btn btn-danger vote-btn" data-action="dislike" data-question-id="<?php echo htmlspecialchars($question['QuestionID']); ?>">Dislike</button>

                        <hr>
                        
                        <h4>Replies</h4>
                        <?php if ($repliesResult->num_rows > 0): ?>
                            <?php while($reply = $repliesResult->fetch_assoc()): ?>
                                <div class="reply">
                                    <p><strong><?php echo htmlspecialchars($reply['username']); ?>:</strong> <?php echo nl2br(htmlspecialchars($reply['AnswerContent'])); ?></p>
                                    <p>Posted on: <?php echo htmlspecialchars($reply['postedTime']); ?></p>
                                    
                                    <!-- Like/Dislike buttons for replies -->
                                    <!-- <button class="btn btn-success vote-btn" data-action="like" data-answer-id="<?php echo htmlspecialchars($reply['AnswerID']); ?>">Like</button>
                                    <span class="vote-count" id="vote-count-reply-<?php echo htmlspecialchars($reply['AnswerID']); ?>"><?php echo $reply['totalVotes']; ?></span>
                                    <button class="btn btn-danger vote-btn" data-action="dislike" data-answer-id="<?php echo htmlspecialchars($reply['AnswerID']); ?>">Dislike</button> -->

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
                            <button type="submit" class="btn btn-primary mt-2">Post Reply</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>