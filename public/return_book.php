<?php
require_once '../config/auth.php';
require_once '../config/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
redirectIfNotAuthenticated('login.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $borrow_id = $_POST['borrow_id'];
    
    // Fetch the book ID associated with this borrow ID
    $stmt = $conn->prepare("SELECT book_id FROM borrowed_books WHERE id = ?");
    $stmt->bind_param("i", $borrow_id);
    $stmt->execute();
    $stmt->bind_result($book_id);
    $stmt->fetch();
    $stmt->close();

    // Update the returned_at field in the borrowed_books table
    $stmt = $conn->prepare("UPDATE borrowed_books SET returned_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $borrow_id);
    if ($stmt->execute()) {
        // Increment the available copies in the books table
        $stmt = $conn->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id = ?");
        $stmt->bind_param("i", $book_id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Book returned successfully!";
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
        }
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    header("Location: dashboard.php");
    exit();
}
?>