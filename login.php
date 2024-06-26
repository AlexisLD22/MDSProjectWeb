<?php
require_once 'include/class/users.php';

if (isset($_POST['login'])) {
    $u = new User();
    $u->login(
        $_POST['email'],
        $_POST['password']
    );
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="assets/style.css">
</head>
<body>
    <form method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>
        <!-- pierre.dupont@example.com -->
        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>
        <!-- P@ssw0rd1 -->
        
        <!-- Error message div -->
        <?php if (isset($_SESSION["error_message"])) : ?>
            <div class="error-message"><?= $_SESSION["error_message"]; ?></div>
        <?php endif; ?> <br>
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>
