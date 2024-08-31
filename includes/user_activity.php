<?php
// Include your configuration file
require_once '../includes/config.php';

// Fetch user ID from session (assuming user is logged in and ID is stored in session)
$userID = $_SESSION['userID'];

// Fetch user details (username, profile picture)
$userQuery = "SELECT username, profile_picture FROM users WHERE id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("i", $userID);
$userStmt->execute();
$userStmt->bind_result($username, $profilePicture);
$userStmt->fetch();
$userStmt->close();

// Fetch user stats (posts, replies, total votes)
$postQuery = "SELECT COUNT(*) FROM questions WHERE userID = ?";
$postStmt = $conn->prepare($postQuery);
$postStmt->bind_param("i", $userID);
$postStmt->execute();
$postStmt->bind_result($postCount);
$postStmt->fetch();
$postStmt->close();

$replyQuery = "SELECT COUNT(*) FROM answers WHERE userID = ?";
$replyStmt = $conn->prepare($replyQuery);
$replyStmt->bind_param("i", $userID);
$replyStmt->execute();
$replyStmt->bind_result($replyCount);
$replyStmt->fetch();
$replyStmt->close();

$voteQuery = "
    SELECT 
        IFNULL(SUM(v.voteValue), 0) AS totalVotes
    FROM 
        votes v
    JOIN 
        answers a ON v.AnswerID = a.AnswerID
    WHERE 
        a.userID = ?";
$voteStmt = $conn->prepare($voteQuery);
$voteStmt->bind_param("i", $userID);
$voteStmt->execute();
$voteStmt->bind_result($totalVotes);
$voteStmt->fetch();
$voteStmt->close();

$conn->close();
?>

<!-- link js -->
 <script src="../js/updatePfp.js"></script>

<!-- User Activity Display -->
<div class="user-activity">
    <div class="user-profile mt-5">
        <img src="../<?php echo htmlspecialchars($profilePicture); ?>" alt="<?php echo htmlspecialchars($profilePicture); ?>" class="profile-picture" id="profilePicture" data-toggle="modal" data-target="#profileModal" style="cursor: pointer;">
        <h2><?php echo htmlspecialchars($username); ?></h2>
    </div>
    <div class="user-stats">
        <p><strong>Posts:</strong> <?php echo $postCount; ?></p>
        <p><strong>Replies:</strong> <?php echo $replyCount; ?></p>
        <p><strong>Total Votes:</strong> <?php echo $totalVotes; ?></p>
    </div>
</div>

<!-- Profile Picture Update Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Change Profile Picture</h5>
                <i class="bi bi-x-lg close" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body">
                <form id="profilePictureForm" action="../includes/update_profile_picture.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="profilePictureInput" class="d-block">
                        <img src="../<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" id="profilePicturePreview" class="profile-picture-input" >

                        </label>
                        <input type="file" class="d-none" name="profile_picture" id="profilePictureInput" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-change-pfp mt-3">Update Picture</button>
                </form>
            </div>
        </div>
    </div>
</div>




