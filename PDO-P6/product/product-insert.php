<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login-user.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $omschrijving = $_POST['omschrijving'];
    $prijsPerStuk = $_POST['prijsPerStuk'];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $uploadDir = '../user/uploads/';
        $fotoNaam = basename($_FILES['foto']['name']);
        $uploadPath = $uploadDir . $fotoNaam;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadPath)) {
            $fotoUrl = $uploadPath;
        } else {
            die('Foto uploaden mislukt.');
        }
    } else {
        die('Geen foto ontvangen.');
    }

    $conn = new mysqli("localhost", "root", "", "p6");

    if ($conn->connect_error) {
        die("Verbinding mislukt: " . $conn->connect_error);
    }

    $sql = "INSERT INTO producten (code, omschrijving, foto, prijsPerStuk) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssd", $code, $omschrijving, $fotoUrl, $prijsPerStuk);

    if ($stmt->execute()) {
        echo "Product succesvol toegevoegd!";
    } else {
        echo "Er is iets fout gegaan bij het toevoegen van het product.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuw Product Toevoegen</title>
</head>
<body>
    <h1>Nieuw Product Toevoegen</h1>

    <form action="product-insert.php" method="POST" enctype="multipart/form-data">
        <label for="code">Code:</label><br>
        <input type="text" name="code" id="code" required><br><br>

        <label for="omschrijving">Omschrijving:</label><br>
        <textarea name="omschrijving" id="omschrijving" required></textarea><br><br>

        <label for="prijsPerStuk">Prijs per Stuk:</label><br>
        <input type="number" name="prijsPerStuk" id="prijsPerStuk" step="0.01" required><br><br>

        <label for="foto">Foto:</label><br>
        <input type="file" name="foto" id="foto" required><br><br>

        <button type="submit">Product Toevoegen</button>
    </form>

    <br>
    <a href="../user/dashboard-user.php">Terug naar Dashboard</a>
</body>
</html>
