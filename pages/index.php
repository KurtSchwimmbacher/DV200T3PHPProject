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
   <div class="container">
        <div class="row">
            <div class="col-12 mt-5">
                <h1 class="index-title">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            </div>
        </div>
   </div>
    
</main>

<?php include '../includes/footer.php'; ?>
