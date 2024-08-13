<!-- signup.php -->
<?php include 'includes/header.php'; ?>

<main>
    <h1>Sign Up</h1>
    <form action="process_signup.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Sign Up</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
