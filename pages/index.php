<!-- index.php -->
<?php include '../includes/header.php'; ?>

<!-- if logged in functionality -->
<?php 
session_start();

if (!isset($_SESSION['username'])) {
    // Redirect if not logged in
    header("Location: ../pages/login.php");
    exit();
}
?>

<!-- link css -->
<link href="../css/header.css" rel="stylesheet">

<main class="main-content">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>This is the home page.</p>
    
</main>

<?php include '../includes/footer.php'; ?>
