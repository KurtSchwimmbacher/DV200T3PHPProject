<!-- feed.php -->
<?php 
require_once '../includes/config.php'; 

if (!isset($_SESSION['username'])) {
    // Redirect if not logged in
    header("Location: ../pages/login.php");
    exit();
}

$userID = $_SESSION['userID'];

// Determine the active tab
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'questions';

if($tab === 'questions'){
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
}
else if($tab === 'answers'){
        // Fetch all asnwers for the logged-in user
        $sql = "SELECT a.*, 
        (SELECT QuestionTitle FROM questions q WHERE q.QuestionID = a.QuestionID) as QuestionTitle,
        COALESCE((SELECT SUM(v.voteValue) FROM votes v WHERE v.AnswerID = a.AnswerID), 0) as totalVotes
    FROM answers a
    WHERE a.UserID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $userID);
$stmt->execute();
$result = $stmt->get_result();
}

// Fetch user activities
$sql = "SELECT ua.*, u.Username 
    FROM user_activities ua
    JOIN users u ON ua.UserID = u.id
    WHERE ua.UserID = ?
    ORDER BY ua.Timestamp DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $userID);
$stmt->execute();
$activityResult = $stmt->get_result();


?>

<?php include '../includes/header.php'; ?>



<!-- link css -->
<link href="../css/userActivity.css" rel="stylesheet">

<!-- link js -->
<script src="../js/vote.js"></script>

<main class="main-content">
    <?php include '../includes/user_activity.php'; ?>
    <div class="index-title-con">
        <h1 class="index-title">Your Activity</h1>
    </div>

    <div class="container">

        <ul class="nav nav-tabs">
            <li class="nav-item nav-item-tab">
                <a class="nav-link nav-link-tab <?= !isset($_GET['tab']) || $_GET['tab'] === 'questions' ? 'active-tab' : '' ?>" 
                href="?tab=questions">Questions</a>
            </li>
            <li class="nav-item nav-item-tab">
                <a class="nav-link nav-link-tab <?= isset($_GET['tab']) && $_GET['tab'] === 'answers' ? 'active-tab' : '' ?>" 
                href="?tab=answers">Answers</a>
            </li>
            </ul>

        <div class="row">
            <div class="col-md-8">
            <?php if ($tab === 'questions'): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-8">
                                    <div class="filtered-bg"></div>
                                    <div class="card-body">
                                        <a href="../pages/singleQuestion.php?questionID=<?= htmlspecialchars($row['QuestionID']) ?>">
                                            <h5 class="card-title"><?= htmlspecialchars($row['QuestionTitle']) ?></h5>
                                        </a>
                                        <p class="card-text"><?= htmlspecialchars($row['QuestionBody']) ?></p>
                                        <p class="card-text">
                                            <small class="text-muted">Replies: <?= $row['reply_count'] ?> | Votes: <?= $row['totalVotes'] ?></small>
                                        </p>
                                        <a href="../includes/updatePost.php?id=<?= $row['QuestionID'] ?>" class="btn btn-update">Update</a>
                                        <a href="../includes/deletePost.php?id=<?= $row['QuestionID'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this question?');">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php elseif ($tab === 'answers'): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-8">
                                    <div class="filtered-bg"></div>
                                    <div class="card-body">
                                        <a href="../pages/singleQuestion.php?questionID=<?= htmlspecialchars($row['QuestionID']) ?>">
                                            <h5 class="card-title"><?= htmlspecialchars($row['QuestionTitle']) ?></h5>
                                        </a>
                                        <p class="card-text">Your Reply: </br><?= htmlspecialchars($row['AnswerContent']) ?></p>
                                        <p class="card-text">Posted on: </br><?= htmlspecialchars($row['postedTime']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>


            <div class="col-md-4">
                <div class="create-new-post">
                <button class="btn btn-new-post mb-3 vote-btn" >
                    <a id="createNewPost" class="link-to-new-post" href="../pages/createPost.php">
                        Create New Post
                    </a>
                </button>
                </div>
                
                <div class="activity-section">
                    <h2 class="recent-activity-title mb-2">Recent Activity</h2>
                    <ul class="list-group-feed">
                        <?php while ($activity = $activityResult->fetch_assoc()): ?>
                            <li class="list-group-item mb-4 mt-3">
                                <?php 
                                // Determine the type of activity being logged to format log
                                if($activity['ActivityType']==='Posted Question'){
                                    // For "Posted Question" activity type
                                    echo '<strong>' . htmlspecialchars($activity['ActivityType']) . ':</strong> ';
                                    echo htmlspecialchars("You " . $activity['ActivityDetails']);
                                }
                                else if($activity['ActivityType']==='Post Moderated'){
                                    // For "Post Moderated" activity type
                                    echo '<strong>' . htmlspecialchars($activity['ActivityType']) . ':</strong> ';
                                    echo htmlspecialchars("Your " . $activity['ActivityDetails']);
                                }
                                ?>
                                
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</main>


<?php include '../includes/filters.php'; ?>
<?php include '../includes/footer.php'; ?>
