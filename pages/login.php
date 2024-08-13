<!-- login.php -->
<?php include 'includes/header.php'; ?>

<main>
    <h1>Login</h1>
    <form action="process_login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Login</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
