<!-- login.php -->
<?php include '../includes/header.php'; ?>

<!-- login functionality -->
<?php 
session_start();
require '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['loginUsername']; // This could be either username or email
    $password = $_POST['loginPassword'];

    // Modify SQL to check for either username or email
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);

    // Bind the input (username/email) twice to the SQL statement
    $stmt->bind_param("ss", $input, $input);

    // Execute SQL statement
    $stmt->execute();

    // Store the result in the result variable
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            // Redirect to home page
            header("Location: index.php");
            exit();
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Invalid username or password";
    }

    $stmt->close();
    $conn->close();
}
?>




<!-- link css -->
<link href="../css/login.css" rel="stylesheet">

<!-- html -->
<main>

    <!-- Blob images -->
    <img src="../assets/blob1.svg" id="blueBlob" class="blue-blob" alt="Rotating Blob">
    <img src="../assets/blob2.svg" id="yellowBlob" class="yellow-blob" alt="Rotating Blob">
    

    <div class="main-content">
        <!-- bootstrap cols/rows -->
        <div class="container">
            <div class="row">
                <!-- used to space the login form in the center -> empty col -->
                <div class="col-3"></div>

                <div class="col-6 mt-5">
                    <!-- form title -->
                    <div class="login-con mt-5">
                        <h1>Login</h1>
                        <!-- form start -->
                        <form action="" method="POST">
                            <div class="mb-4 mt-4">
                                <input type="text" class="form-control" name="loginUsername" id="loginUsername" placeholder="Email or username" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="loginPassword" id="loginPassword" placeholder="Password" required>
                            </div>
                            <div class="remember-me">
                                <label class="switch mb-3">
                                    <input type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                                <p>Remember me</p>
                            </div>
                            <button type="submit" class="btn btn-login mt-4">Login</button>
                        </form>
                        <!-- form end -->

                        <!-- links to login page -->
                        <div class="login-form-extent mt-4 mb-1">
                            <!-- &#160 is unicode for a blank space -> helps spacing look nicer -->
                            <p class="no-account-text">&#160OR&#160</p>
                            <p class="create-account-text">Don't have an account?</p>
                            <!-- button wrapped in a tag to link to sign up page -->
                            <a href="signup.php">
                                <button type="button" class="btn btn-create">Create Account</button>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- used to space the login form in the center -> empty col -->
                <div class="col-3"></div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
