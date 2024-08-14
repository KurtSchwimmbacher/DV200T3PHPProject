<?php
$servername = "localhost";
$username = "root"; // replace with your MySQL username
$password = ""; // replace with your MySQL password
$dbname = "movie_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission and movie upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];
    $summary = $_POST['summary'];

 // Handle file upload
 $target_dir = "../uploads/";
 $target_file = $target_dir . basename($_FILES["picture"]["name"]);
 if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
     // Prepare an SQL statement for insertion
     $stmt = $conn->prepare("INSERT INTO movies (title, genre, release_date, summary, picture) VALUES (?, ?, ?, ?, ?)");
     $stmt->bind_param("sssss", $title, $genre, $release_date, $summary, $target_file);

     // Execute the statement
     if ($stmt->execute()) {
         echo "<script>alert('New movie uploaded successfully');</script>";
     } else {
         echo "Error: " . $stmt->error;
     }

     // Close the statement
     $stmt->close();
 } else {
     echo "Sorry, there was an error uploading your file.";
 }
}

// Handle movie deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM movies WHERE id=$delete_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Movie deleted successfully');</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch all movies
$sql = "SELECT id, title, genre, release_date, summary, picture FROM movies";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        form {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 600px;
            padding: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            background: #007bff;
            border: none;
            color: white;
            cursor: pointer;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        .movie-card {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 600px;
            overflow: hidden;
            padding: 20px;
            text-align: left;
        }
        .movie-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .movie-card h2 {
            margin-top: 0;
        }
        .movie-card p {
            margin: 10px 0;
        }
        .delete-btn {
            background: #dc3545;
            border: none;
            color: white;
            cursor: pointer;
            padding: 10px;
            text-align: center;
            width: 100%;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .delete-btn:hover {
            background: #c82333;
        }
    </style>
    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this movie?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'deleteMovie.php?id=' + id, true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert('Movie deleted successfully');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Error deleting movie');
                    }
                };
                xhr.send();
                }
        }
    </script>
</head>
<body>
    <h1>Movie Management</h1>
    
    <!-- Movie Upload Form -->
    <form action="movieUploads.php" method="post" enctype="multipart/form-data">
        <label for="title">Movie Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="genre">Movie Genre:</label>
        <input type="text" id="genre" name="genre" required><br><br>

        <label for="release_date">Release Date:</label>
        <input type="date" id="release_date" name="release_date" required><br><br>

        <label for="summary">Movie Summary:</label>
        <textarea id="summary" name="summary" required></textarea><br><br>

        <label for="picture">Movie Picture:</label>
        <input type="file" id="picture" name="picture" required><br><br>

        <input type="submit" name="submit" value="Upload">
    </form>

    <!-- Movie List -->
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='movie-card'>";
            echo "<h2>" . $row["title"] . "</h2>";
            echo "<p><strong>Genre:</strong> " . $row["genre"] . "</p>";
            echo "<p><strong>Release Date:</strong> " . $row["release_date"] . "</p>";
            echo "<p><strong>Summary:</strong> " . $row["summary"] . "</p>";
            echo "<img src='" . $row["picture"] . "' alt='" . $row["title"] . "'><br>";
            echo "<button class='delete-btn' onclick='confirmDelete(" . $row["id"] . ")'>Delete</button>";
            echo "</div>";
        }
    } else {
        echo "No movies found.";
    }
    $conn->close();
    ?>
</body>
</html>