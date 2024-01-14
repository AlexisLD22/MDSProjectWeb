<?php
require_once 'include/session.php';
require_once 'include/conn.php';

$_SESSION['is_logged_in'] = false; // Set the session variable to indicate logout
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
// Redirect the user to the login page
header("Location: login.php");
exit();
?>