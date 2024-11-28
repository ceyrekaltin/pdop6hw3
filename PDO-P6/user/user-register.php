<?php
require_once("../includes/db.php");
require_once("../includes/user-class.php");

$dbh = new DB();
$user = new User($dbh);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $naam = $_POST['naam'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->registerUser($naam, $email, $password)) {
        echo "Registratie succesvol!";
    } else {
        echo "Er is iets misgegaan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="POST">
        <input type="text" name="naam" placeholder="Naam" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login-user.php">Login</a></p>
</body>
</html>
