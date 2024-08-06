<?php
require_once '../config/auth.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
redirectIfNotAuthenticated('login.php');

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];
    $due_date = date('Y-m-d', strtotime('+2 weeks')); // Set due date to 2 weeks from now

    // Check if the book is available
    $check_stmt = $conn->prepare("SELECT available_copies FROM books WHERE id = ?");
    $check_stmt->bind_param("i", $book_id);
    $check_stmt->execute();
    $check_stmt->bind_result($available_copies);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($available_copies > 0) {
        // Update the available copies
        $update_stmt = $conn->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE id = ?");
        $update_stmt->bind_param("i", $book_id);
        $update_stmt->execute();
        $update_stmt->close();

        // Insert into borrowed_books
        $borrow_stmt = $conn->prepare("INSERT INTO borrowed_books (user_id, book_id, due_date) VALUES (?, ?, ?)");
        $borrow_stmt->bind_param("iis", $user_id, $book_id, $due_date);
        
        try {
            $borrow_stmt->execute();
            $_SESSION['message'] = "Book borrowed successfully!";
        } catch (mysqli_sql_exception $e) {
            $_SESSION['message'] = "Error: " . $e->getMessage();
        }

        $borrow_stmt->close();
    } else {
        $_SESSION['message'] = "Sorry, this book is not available.";
    }

    header("Location: dashboard.php");
    exit();
}
?>