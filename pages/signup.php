<!-- signup.php -->
<?php include '../includes/header.php'; ?>


<!-- sign up functionality -->
<?php 
session_start();
require '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['signUpUsername'];
    $email = $_POST['signUpEmail'];
    $password = $_POST['signUpPassword'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        // echo "Registration Complete";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!-- link css -->
<link href="../css/signup.css" rel="stylesheet">
<link href="../css/login.css" rel="stylesheet">

<!-- html -->
<main>
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6 mt-5">
                <div class="login-con mt-5">
                    <h1>Sign Up</h1>
                    <form action="" method="POST" onsubmit="return validateForm()">
                        <div class="mb-4 mt-4">
                            <input type="text" class="form-control" name="signUpUsername" id="signUpUsername" placeholder="Username" required>
                        </div>
                        <div class="mb-4 mt-4">
                            <input type="email" class="form-control" name="signUpEmail" id="signUpEmail" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" name="signUpPassword" id="signUpPassword" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="signUpPasswordConfirm" placeholder="Confirm Password" required>
                        </div>
                        <button type="submit" class="btn btn-signup mt-4">Sign Up</button>
                    </form>

                    <div class="login-form-extent mt-4 mb-1">
                        <p class="no-account-text">&#160OR&#160</p>
                        <p class="create-account-text">Already have an account?</p>
                        <a href="login.php">
                            <button class="btn btn-signUp">Log In</button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-3"></div>
        </div>
    </div>
    </div>

</main>

<?php include '../includes/footer.php'; ?>
