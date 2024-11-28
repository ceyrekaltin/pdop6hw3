<?php
session_start();

require_once("../includes/db.php");
require_once("../includes/user-class.php");

$dbh = new DB();
$user = new User($dbh);

$loginError = "";

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: dashboard-user.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->loginUser($email, $password)) {
        $_SESSION['logged_in'] = true;
        header("Location: dashboard-user.php");
        exit();
    } else {
        $loginError = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (!empty($loginError)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($loginError); ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="user-register.php">Register</a></p>
</body>
</html>
