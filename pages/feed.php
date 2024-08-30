<?php 
require_once '../includes/functions.php'; // Move function definition here
require_once '../includes/config.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: ../pages/login.php");
    exit();
}

// Fetch all approved questions with the number of replies
$sql = "SELECT q.*, 
               u.username,
               (SELECT GROUP_CONCAT(t.TagName) 
                FROM question_tags qt 
                JOIN tags t ON qt.TagID = t.TagID 
                WHERE qt.QuestionID = q.QuestionID) as tags,
               (SELECT COUNT(*) FROM answers a WHERE a.QuestionID = q.QuestionID) as reply_count,
               COALESCE((SELECT SUM(v.voteValue) 
                         FROM votes v 
                         WHERE v.AnswerID IN (SELECT AnswerID FROM answers WHERE QuestionID = q.QuestionID)), 0) as totalVotes
        FROM questions q 
        JOIN users u ON q.UserID = u.id
        WHERE q.isApproved = 'approved'";

$result = $conn->query($sql);
?>

<?php include '../includes/header.php'; ?>

<link href="../css/header.css" rel="stylesheet">
<link href="../css/tag-styles.css" rel="stylesheet">
<script src="../js/vote.js"></script>

<main class="main-content">
    <div class="index-title-con">
        <h1 class="index-title">Feed</h1>
    </div>

    <div class="container">
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-12 mb-4">
                        <div class="card horizontal-card">
                            <div class="row no-gutters">
                                <div class="col-md-8">
                                    <div class="filtered-bg"></div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            <small class="text-muted"><?php echo htmlspecialchars($row['username']); ?></small>
                                        </p>

                                        <?php if (!empty($row['tags'])): ?>
                                            <p class="card-text">
                                                <?php 
                                                $tags = explode(',', $row['tags']); 

                                                foreach ($tags as $tag): 
                                                    $class = getTagClass($tag);
                                                ?>
                                                    <span class="badge <?php echo htmlspecialchars($class); ?>">
                                                        <?php echo htmlspecialchars(trim($tag)); ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </p>
                                        <?php endif; ?>

                                        <a href="../pages/singleQuestion.php?questionID=<?php echo htmlspecialchars($row['QuestionID']); ?>">
                                            <h5 class="card-title"><?php echo htmlspecialchars($row['QuestionTitle']); ?></h5>
                                        </a>
                                        <p class="card-text"><?php echo nl2br(htmlspecialchars($row['QuestionBody'])); ?></p>

                                        <p>
                                            <span class="reply-count">
                                                <?php echo $row['reply_count']; ?> Replies
                                            </span>
                                        </p>

                                        <button class="btn btn-like vote-btn" data-action="like" data-question-id="<?php echo htmlspecialchars($row['QuestionID']); ?>"><i class="bi bi-arrow-up"></i></button>
                                        <span class="vote-count" id="vote-count-<?php echo htmlspecialchars($row['QuestionID']); ?>"><?php echo $row['totalVotes']; ?></span>
                                        <button class="btn btn-dislike vote-btn" data-action="dislike" data-question-id="<?php echo htmlspecialchars($row['QuestionID']); ?>"><i class="bi bi-arrow-down"></i></button>
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

<?php include '../includes/filters.php'; ?>
<?php include '../includes/footer.php'; ?>
