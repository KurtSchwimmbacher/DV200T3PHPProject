<?php 
require_once '../includes/config.php';

// Fetch the 6 most recent posts
$sql_recent = "SELECT q.*, 
                        u.username, u.profile_picture,
                      (SELECT COUNT(*) FROM answers a WHERE a.QuestionID = q.QuestionID) as reply_count,
                      COALESCE((SELECT SUM(v.voteValue) FROM votes v WHERE v.AnswerID IN (SELECT AnswerID FROM answers WHERE QuestionID = q.QuestionID)), 0) as totalVotes
               FROM questions q 
               JOIN users u on q.UserID = u.id
               WHERE q.isApproved = 'approved'
               ORDER BY q.createdAt DESC
               LIMIT 6";
$stmt_recent = $conn->prepare($sql_recent);
$stmt_recent->execute();
$result_recent = $stmt_recent->get_result();

// Fetch the 6 most liked posts
$sql_liked = "SELECT q.*, 
                    u.username,u.profile_picture,
                     (SELECT COUNT(*) FROM answers a WHERE a.QuestionID = q.QuestionID) as reply_count,
                     COALESCE((SELECT SUM(v.voteValue) FROM votes v WHERE v.AnswerID IN (SELECT AnswerID FROM answers WHERE QuestionID = q.QuestionID)), 0) as totalVotes
              FROM questions q 
              JOIN users u on q.UserID = u.id
              WHERE q.isApproved = 'approved'
              ORDER BY totalVotes DESC
              LIMIT 6";
$stmt_liked = $conn->prepare($sql_liked);
$stmt_liked->execute();
$result_liked = $stmt_liked->get_result();
?>

<?php include '../includes/header.php'; ?>

<!-- link css -->
<link href="../css/header.css" rel="stylesheet">

<!-- link js -->
<script src="../js/vote.js"></script>

<main class="main-content">
    <div class="index-title-con">
        <h1 class="index-title">
            Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>!
        </h1>   
    </div>

    <div class="container">
        <!-- Most Recent Posts -->
        <div class="row mb-4">
            <h2 class="home-pg-titles">Most Recent Posts</h2>
            <?php if ($result_recent->num_rows > 0): ?>
                <?php while($row = $result_recent->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">

                        <div class="card">
                            <div class="filtered-bg"></div>

                            <div class="card-body">
                                <p class="card-text card-username">
                                    <img class="pfp-index" src="../<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="" >
                                    <small class="text-muted"><?php echo htmlspecialchars($row['username']) ?></small>
                                </p>
                                <?php if ($row['questionImg']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($row['questionImg']); ?>" alt="Question Image" class="card-img-top">
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
                            </div>
                        </div>

                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No questions have been posted yet.</p>
            <?php endif; ?>
        </div>

        <!-- Most Liked Posts -->
        <div class="row mb-4">
            <h2 class="home-pg-titles">Most Liked Posts</h2>
            <?php if ($result_liked->num_rows > 0): ?>
                <?php while($row = $result_liked->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                        <div class="filtered-bg"></div>
                        
                            <div class="card-body">
                                <!-- display username -->
                                <p class="card-text card-username">
                                <img class="pfp-index" src="../<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="" >
                                    <small class="text-muted"><?php echo htmlspecialchars($row['username']) ?></small>
                                </p>
                                <?php if ($row['questionImg']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($row['questionImg']); ?>" alt="Question Image" class="card-img-top index-card-img">
                                <?php endif; ?>
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
