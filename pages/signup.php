<!-- signup.php -->
<?php include '../includes/header.php'; ?>


<!-- sign up functionality -->
<?php 
require_once '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['signUpUsername'];
    $email = $_POST['signUpEmail'];
    $password = $_POST['signUpPassword'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // File upload handling
    $profilePicture = $_FILES['profilePicture'];
    $profilePicturePath = '';

    if ($profilePicture['error'] === UPLOAD_ERR_OK) {
        // Define the upload directory
        $uploadDir = '../uploads/profile_pictures/';
        // Create the directory if it does not exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        // Generate a unique file name
        $profilePicturePath = $uploadDir . uniqid() . '_' . basename($profilePicture['name']);
        // Move the uploaded file to the upload directory
        if (!move_uploaded_file($profilePicture['tmp_name'], $profilePicturePath)) {
            echo "Error uploading profile picture.";
            exit;
        }
    } else {
        // Set default profile picture if no file is uploaded
        $profilePicturePath = 'default_profile.png';
    }

    // Prepare the SQL statement with profile picture
    $sql = "INSERT INTO users (username, email, password, profile_picture) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $profilePicturePath);

    if ($stmt->execute()) {
        // Registration success
        // Optionally, you can redirect the user to a login page or home page
        header('Location: login.php');
        exit;
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
            <div class="col-12 mt-3">
                <div class="signup-con mt-5">
                    <h1 class="login-title mb-4">&#16Sign Up&#16</h1>
                    <form class="login-form" action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <div class="mt-3">
                            <input type="text" class="form-control" name="signUpUsername" id="signUpUsername" placeholder="Username" required>
                        </div>
                        <div class="mt-3">
                            <input type="email" class="form-control" name="signUpEmail" id="signUpEmail" placeholder="Email" required>
                        </div>
                        <div class="mt-3">
                            <input type="password" class="form-control" name="signUpPassword" id="signUpPassword" placeholder="Password" required>
                        </div>
                        <div class="mt-3">
                            <input type="password" class="form-control" id="signUpPasswordConfirm" placeholder="Confirm Password" required>
                        </div>
                        <div class="mt-3">
                            <input type="file" class="form-control" name="profilePicture" id="profilePicture" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-signup mt-3">Sign Up</button>
                    </form>


                    <div class="login-form-extent mt-4 mb-1">
                        <p class="no-account-text">&#160OR&#160</p>
                        <p class="create-account-text">Already have an account?</p>
                        <a href="login.php">
                            <button type="button" class="btn btn-signUp">Log In</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</main>

<?php include '../includes/footer.php'; ?>
