<?php 
require_once '../functionality/getTagClass.php';
require_once '../includes/config.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: ../pages/login.php");
    exit();
}

// Fetch all approved questions with the number of replies
// Get filter and sort parameters
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';

// Prepare base SQL query
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

// Add search condition
if ($search) {
    $sql .= " AND (q.QuestionTitle LIKE '%$search%' OR q.QuestionBody LIKE '%$search%')";
}

// Add tag filter condition
if (!empty($_GET['tags']) && !in_array('all', $_GET['tags'])) {
    $tags = array_map([$conn, 'real_escape_string'], $_GET['tags']);
    $tagConditions = array_map(function($tag) use ($conn) {
        return "t.TagName = '{$conn->real_escape_string($tag)}'";
    }, $tags);
    $sql .= " AND EXISTS (
                SELECT 1 
                FROM question_tags qt 
                JOIN tags t ON qt.TagID = t.TagID 
                WHERE qt.QuestionID = q.QuestionID 
                AND (" . implode(' OR ', $tagConditions) . ")
            )";
}

// Add sorting condition
switch ($sort) {
    case 'date_desc':
        $sql .= " ORDER BY q.createdAt DESC";
        break;
    case 'date_asc':
        $sql .= " ORDER BY q.createdAt ASC";
        break;
    case 'votes_desc':
        $sql .= " ORDER BY totalVotes DESC";
        break;
    case 'votes_asc':
        $sql .= " ORDER BY totalVotes ASC";
        break;
    default:
        $sql .= " ORDER BY q.dateCreated DESC"; // Default sort
}

// Fetch results
$result = $conn->query($sql);


?>


<?php include '../includes/header.php'; ?>

<!-- link to css -->
<link href="../css/header.css" rel="stylesheet">
<link href="../css/tag-styles.css" rel="stylesheet">

<!-- link to js -->
<script src="../js/vote.js"></script>
<script src="../js/filterFeed.js"></script>

<main class="main-content">
    <div class="index-title-con">
        <h1 class="index-title">Feed</h1>
    </div>

    <div class="container">
        <div class="row mb-5">
            <div class="col-12">
                <form id="filter-form">
                    <div class="row">
                        <div class="col-8"></div>
                        <div class="col-4 mb-5">
                            <div class="form-group">
                                <label for="search">Search:</label>
                                <div class="input-group">
                                    <div class="input-group-text"><i class="bi bi-search"></i></div>
                                    <input type="text" id="search" name="search" class="form-control feed-search" placeholder="Search questions" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="sort">Sort By:</label>
                                <select id="sort" name="sort" class="form-select sort-select ">
                                    <option class="sort-opt" value="date_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date_desc') ? 'selected' : ''; ?>>Date desc</option>
                                    <option class="sort-opt" value="date_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date_asc') ? 'selected' : ''; ?>>Date asc</option>
                                    <option class="sort-opt" value="votes_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'votes_desc') ? 'selected' : ''; ?>>Votes desc</option>
                                    <option class="sort-opt" value="votes_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'votes_asc') ? 'selected' : ''; ?>>Votes asc</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4"></div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="tags"><i class="bi bi-filter"></i> Filter by Tags:</label>
                                <select id="tags" name="tags[]" class="form-select filter-select ">
                                    <option class="filter-opt" value="all">All</option>
                                    <?php
                                    $availableTags = ['New to Game', 'Advice', 'Help', 'Rules', 'Strategy'];
                                    foreach ($availableTags as $tag): 
                                        $selected = isset($_GET['tags']) && in_array($tag, $_GET['tags']) ? 'selected' : '';
                                    ?>
                                        <option class="filter-opt" value="<?php echo htmlspecialchars($tag); ?>" <?php echo $selected; ?>>
                                            <?php echo htmlspecialchars($tag); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-2 mb-3">
            <div class="col-4">
            </div>
        </div>
            

        <div id="results" class="row">
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
