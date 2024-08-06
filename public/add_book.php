<?php
// Include authentication configuration file
require_once '../config/auth.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if not authenticated
redirectIfNotAuthenticated('login.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book - Library Management System</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body class="centered">
    <div class="addbook-container">
        <h2>Add Book</h2>
        <!-- Form to add a new book -->
        <form method="POST" action="add_book.php">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required><br>
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required><br>
            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" required><br>
            <label for="publication_year">Publication Year:</label>
            <input type="number" id="publication_year" name="publication_year" required><br>
            <label for="available_copies">Available Copies:</label>
            <input type="number" id="available_copies" name="available_copies" required><br>
            <button type="submit">Add Book</button>
        </form>
        <div id="addBookMessage">
            <?php
            // Display message if set in session
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }
            ?>
        </div>
        <!-- Link to go back to home page -->
        <a href="index.php" class="button">Back to Home</a>
    </div>
</body>
</html>

<?php
// Include database configuration file
require_once '../config/db.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $publication_year = $_POST['publication_year']; // Added publication year
    $available_copies = $_POST['available_copies']; // Added available copies

    // Prepare SQL statement to insert new book
    $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, publication_year, available_copies) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $title, $author, $isbn, $publication_year, $available_copies);

    // Execute statement and set session message based on success or failure
    if ($stmt->execute()) {
        $_SESSION['message'] = "Book added successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    // Redirect back to the add book page to show message
    header("Location: add_book.php");
    exit();
}
?>