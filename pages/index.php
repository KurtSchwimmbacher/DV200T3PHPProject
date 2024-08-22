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
<script src="../js/vote.js"></script>

<main class="main-content">
    <div class="index-title-con">
        <h1 class="index-title">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    </div>

    <div class="container">
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-12 mb-4">
                        <div class="card horizontal-card">
                            <div class="row no-gutters">
                                <!-- <div class="col-md-4">
                                    <?php if ($row['questionImg']): ?>
                                        <img src="../uploads/<?php echo htmlspecialchars($row['questionImg']); ?>" alt="Question Image" class="card-img">
                                    <?php endif; ?>
                                </div> -->
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <a href="../pages/singleQuestion.php?questionID=<?php echo htmlspecialchars($row['QuestionID']); ?>">
                                            <h5 class="card-title"><?php echo htmlspecialchars($row['QuestionTitle']); ?></h5>
                                        </a>
                                        <p class="card-text"><?php echo nl2br(htmlspecialchars($row['QuestionBody'])); ?></p>
                                        
                                        <!-- Display the number of replies -->
                                        <p>
                                            <span class="reply-count">
                                                <?php echo $row['reply_count']; ?> Replies
                                            </span>
                                        </p>

                                        <!-- Like/Dislike buttons -->
                                        <button class="btn btn-success vote-btn" data-action="like" data-question-id="<?php echo htmlspecialchars($row['QuestionID']); ?>">Like</button>
                                        <span class="vote-count" id="vote-count-<?php echo htmlspecialchars($row['QuestionID']); ?>"><?php echo $row['totalVotes']; ?></span>
                                        <button class="btn btn-danger vote-btn" data-action="dislike" data-question-id="<?php echo htmlspecialchars($row['QuestionID']); ?>">Dislike</button>
                                    </div>
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
