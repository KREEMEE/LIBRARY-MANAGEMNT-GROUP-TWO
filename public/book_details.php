<?php
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT title, author, publication_year, available_copies FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->bind_result($title, $author, $publication_year, $available_copies);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Details</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body class="centered">
    <div class="form-container">
        <h2>Book Details</h2>
        <p>Title: <?php echo htmlspecialchars($title); ?></p>
        <p>Author: <?php echo htmlspecialchars($author); ?></p>
        <p>Publication Year: <?php echo htmlspecialchars($publication_year); ?></p>
        <p>Available Copies: <?php echo htmlspecialchars($available_copies); ?></p>
        <a class="button" href="index.php">Back to Home</a>
    </div>
</body>
</html>