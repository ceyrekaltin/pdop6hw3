<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login-user.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Product ID niet opgegeven.');
}

$productId = $_GET['id'];

$conn = new mysqli("localhost", "root", "", "p6");

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

$sql = "SELECT * FROM producten WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$productData = $stmt->get_result()->fetch_assoc();

if (!$productData) {
    die('Product niet gevonden.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $omschrijving = $_POST['omschrijving'];
    $prijsPerStuk = $_POST['prijsPerStuk'];
    $fotoUrl = $productData['foto'];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $uploadDir = '../user/uploads/';
        $fotoNaam = time() . "_" . basename($_FILES['foto']['name']);
        $uploadPath = $uploadDir . $fotoNaam;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadPath)) {
            $fotoUrl = $uploadPath;
        } else {
            die('Foto uploaden mislukt.');
        }
    }

    $updateSql = "UPDATE producten SET code = ?, omschrijving = ?, foto = ?, prijsPerStuk = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssssi", $code, $omschrijving, $fotoUrl, $prijsPerStuk, $productId);

    if ($updateStmt->execute()) {
        echo "Product succesvol bijgewerkt!";
    } else {
        echo "Er is iets fout gegaan bij het bijwerken van het product.";
    }

    $updateStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Bewerken</title>
</head>
<body>
    <h1>Product Bewerken</h1>

    <form action="product-edit.php?id=<?php echo $productId; ?>" method="POST" enctype="multipart/form-data">
        <label for="code">Code:</label><br>
        <input type="text" name="code" id="code" value="<?php echo htmlspecialchars($productData['code']); ?>" required><br><br>

        <label for="omschrijving">Omschrijving:</label><br>
        <textarea name="omschrijving" id="omschrijving" required><?php echo htmlspecialchars($productData['omschrijving']); ?></textarea><br><br>

        <label for="prijsPerStuk">Prijs per Stuk:</label><br>
        <input type="number" step="0.01" name="prijsPerStuk" id="prijsPerStuk" value="<?php echo htmlspecialchars($productData['prijsPerStuk']); ?>" required><br><br>

        <label for="foto">Foto:</label><br>
        <input type="file" name="foto" id="foto"><br>
        <?php if (!empty($productData['foto'])): ?>
            <p>Huidige foto:</p>
            <img src="<?php echo htmlspecialchars($productData['foto']); ?>" alt="Product Foto" width="100"><br>
        <?php endif; ?>

        <br><br>
        <button type="submit">Product Bijwerken</button>
    </form>

    <br>
    <a href="../user/dashboard-user.php">Terug naar Dashboard</a>
</body>
</html>
