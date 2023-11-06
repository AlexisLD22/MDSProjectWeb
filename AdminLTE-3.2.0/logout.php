<?php
session_start(); // Start a new session or resume the existing session

// Check if the user is logged in
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    // User is logged in, so log them out
    $_SESSION['is_logged_in'] = false; // Set the session variable to indicate logout
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session

    // Redirect the user to the login page
    header("Location: login.php");
    exit();
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}
?>