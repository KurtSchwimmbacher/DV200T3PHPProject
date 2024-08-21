<!-- index.php -->
<?php include '../includes/header.php'; ?>

<!-- if logged in functionality -->
<?php 
require_once '../includes/config.php'; // Ensure this is at the very top

if (!isset($_SESSION['username'])) {
    // Redirect if not logged in
    header("Location: ../pages/login.php");
    exit();
}
?>

<!-- link css -->
<link href="../css/header.css" rel="stylesheet">

<main class="main-content">
    <div class="index-title-con">
        <h1 class="index-title">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    </div>

   <div class="container">
        <div class="row">
            
        </div>
   </div>
    
</main>

<?php include '../includes/footer.php'; ?>
