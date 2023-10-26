<?php
// Check if the login form is submitted
if (isset($_POST['login'])) {
    // Database connection settings (replace with your own)
    $host = "localhost:8889";
    $username = "root";
    $password = "root";
    $database = "toiletagecanin";

    // Create a database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check for database connection error
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Get user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize the inputs (prevent SQL injection)
    $email = $conn->real_escape_string($email);

    // Query the database to check if the email and password match
    $query = "SELECT * FROM users WHERE mail = '$email' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows === 1) {
        // Login successful
        header("Location: index.html");
    } else {
        // Login failed
        $error_message = "Login failed. Please check your email and password.";
    }

    // Close the database connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="assets/style.css">
</head>
<body>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>

        <!-- Error message div -->
        <?php if (isset($error_message)) : ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?> <br>
        <input type="submit" name="login" value="Login">
    </form>

</body>
</html>
