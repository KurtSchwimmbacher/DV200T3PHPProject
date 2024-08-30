<!-- adminApprove.php -->
<?php

require_once '../includes/config.php';

// Check if the user is an admin
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 1) {
    header("Location: ../pages/login.php");
    exit();
}

// Fetch all questions awaiting approval
$sql = "SELECT q.*, 
               u.username
        FROM questions q 
        JOIN users u ON q.UserID = u.id
        WHERE q.isApproved = 'pending'";

$result = $conn->query($sql);

// Handle approval or denial
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['questionID'])) {
        $questionID = $_POST['questionID'];
        $isApproved = isset($_POST['approve']) ? 'approved' : 'denied'; // Set status based on button clicked
        
        $sql = "UPDATE questions SET isApproved = ? WHERE QuestionID = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("si", $isApproved, $questionID);
            if ($stmt->execute()) {
                // Redirect to the same page to refresh the list
                header("Location: adminApprove.php");
                // echo "Question updated successfully.";
            } else {
                echo "Error updating question: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "No questionID provided.";
    }

    // Close the connection once all operations are complete
    $conn->close();
}
?>


<!-- link css -->
<link href="../css/adminApprove.css" rel="stylesheet">


<!-- HTML -->
<?php include '../includes/header.php'; ?>
<main>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="admin-approve-title mt-3 mb-3">Questions Awaiting Approval</h1>
                </div>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                            <div class="filtered-bg"></div>
                                <div class="card-body">
                                    <!-- Display the username -->
                                    <p class="card-text">
                                        <small class="text-muted"><?php echo htmlspecialchars($row['username']); ?></small>
                                    </p>
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['QuestionTitle']); ?></h5>
                                    <p class="card-text"><?php echo nl2br(htmlspecialchars($row['QuestionBody'])); ?></p>
                                    <?php if ($row['questionImg']): ?>
                                        <img src="../uploads/<?php echo htmlspecialchars($row['questionImg']); ?>" alt="Question Image" class="card-img-top">
                                    <?php endif; ?>
                                    <form method="POST" action="adminApprove.php" class="mt-3">
                                        <input type="hidden" name="questionID" value="<?= htmlspecialchars($row['QuestionID']); ?>">
                                        <button type="submit" name="approve" class="btn btn-approve">Approve</button>
                                        <button type="submit" name="deny" class="btn btn-deny">Deny</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No questions awaiting approval.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/filters.php'; ?>
<?php include '../includes/footer.php'; ?>
