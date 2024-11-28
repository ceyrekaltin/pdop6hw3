<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login-user.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $conn = new mysqli("localhost", "root", "", "p6");

    if ($conn->connect_error) {
        die("Verbinding mislukt: " . $conn->connect_error);
    }

    $sql = "DELETE FROM producten WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: product-view.php?message=Product%20verwijderd");
        exit();
    } else {
        echo "Er is iets fout gegaan bij het verwijderen.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Ongeldige aanvraag.";
}
?>
