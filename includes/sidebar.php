<!-- includes/sidebar.php -->
<?php
require_once '../includes/config.php'; 
?>

<aside>
    <!-- link css file to style sidebar -->
    <link href="../css/sidebar.css" rel="stylesheet">

    <nav class="nav-tag">
        <div class="sidebar">
            <div class="nav-brand">
                <img class="logo-img" src="../assets/vagabond_logo.png" alt="Logo">
            </div>
            <h3 class="brand-name">Forest<b class="green-tactics">Tactics</b></h3>
            <a class="" href="../pages/index.php">Home</a>
            <!-- <a href="../pages/createPost.php">Post</a> -->
            <!-- <a href="../pages/feed.php">Feed</a> -->
            <a href="#about">Browse</a>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 1): ?>
                <!-- Admin link, shown only if the user is an admin -->
                <a href="../pages/adminApprove.php">Admin Approve</a>
            <?php endif; ?>

            <div class="nav-acount">
                <?php if (isset($_SESSION['username'])): ?>
                    <a class="account active" href="../pages/userActivity.php">
                        Account
                        <i class="bi bi-person"></i>
                    </a>
                <?php else: ?>
                    <a class="account active" href="../pages/login.php">
                        Account
                        <i class="bi bi-person"></i>
                    </a>
                <?php endif; ?>

                <a class="log-out" href="../functionality/logout.php">
                    Log Out
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>
</aside>
