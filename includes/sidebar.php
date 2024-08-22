<!-- includes/sidebar.php -->
<?php
require_once '../includes/config.php'; 


// Get the current file name
$current_page = basename($_SERVER['PHP_SELF']);
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
            <a class="<?= $current_page == 'index.php' ? 'active' : '' ?>" href="../pages/index.php">Home</a>
            <!-- <a href="../pages/createPost.php">Post</a> -->
            <a class="<?= $current_page == 'feed.php' ? 'active' : '' ?>" href="../pages/feed.php">Feed</a>
            <!-- <a href="#about">Browse</a> -->

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 1): ?>
                <!-- Admin link, shown only if the user is an admin -->
                <a class="<?= $current_page == 'adminApprove.php' ? 'active' : '' ?>" href="../pages/adminApprove.php">Admin Approve</a>
            <?php endif; ?>

            <div class="nav-acount">
                <?php if (isset($_SESSION['username'])): ?>
                    <a class="<?= $current_page == 'userActivity.php' ? 'active' : '' ?>" href="../pages/userActivity.php">
                        Account
                        <i class="bi bi-person"></i>
                    </a>
                <?php else: ?>
                    <a class="<?= $current_page == 'login.php' ? 'active' : '' ?>" href="../pages/login.php">
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
