<!-- feed.php -->
<?php 
require_once '../includes/config.php'; // Ensure this is at the very top

if (!isset($_SESSION['username'])) {
    // Redirect if not logged in
    header("Location: ../pages/login.php");
    exit();
}

$userID = $_SESSION['userID'];

// Fetch all questions for the logged-in user
$sql = "SELECT q.*, 
               (SELECT COUNT(*) FROM answers a WHERE a.QuestionID = q.QuestionID) as reply_count,
               COALESCE((SELECT SUM(v.voteValue) FROM votes v WHERE v.AnswerID IN (SELECT AnswerID FROM answers WHERE QuestionID = q.QuestionID)), 0) as totalVotes
        FROM questions q 
        WHERE q.UserID = ? AND q.isApproved = 'approved'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $userID);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include '../includes/header.php'; ?>

<!-- link css -->
<!-- <link href="../css/header.css" rel="stylesheet"> -->

<!-- link js -->
<script src="../js/vote.js"></script>

<main class="main-content">
    <div class="index-title-con">
        <h1 class="index-title">Your Activity</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="singleQuestion.php?id=<?= $row['QuestionID'] ?>"><?= htmlspecialchars($row['QuestionTitle']) ?></a>
                                    </h5>
                                    <p class="card-text"><?= htmlspecialchars($row['QuestionBody']) ?></p>
                                    <p class="card-text">
                                        <small class="text-muted">Replies: <?= $row['reply_count'] ?> | Votes: <?= $row['totalVotes'] ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="col-md-4">
                <div class="create-new-post">
                <button class="btn btn-success vote-btn" data-action="dislike" >
                    <a class="link-to-new-post" href="createPost.php">
                        Create New Post
                    </a>
                </button>
                </div>
                <div class="activity-section">
                    <h4>Recent Activity</h4>
                    <ul class="list-group">
                        <!-- Example static activity list items -->
                        <li class="list-group-item">You replied to a question</li>
                        <li class="list-group-item">You upvoted an answer</li>
                        <li class="list-group-item">Your question received a new reply</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
