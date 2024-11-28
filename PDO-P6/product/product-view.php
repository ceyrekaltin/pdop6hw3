<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login-user.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "p6");

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

$sql = "SELECT * FROM producten";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bekijk Producten</title>
</head>
<body>
    <h1>Bekijk Producten</h1>
    <a href="../user/dashboard-user.php">Terug naar dashboard</a>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Omschrijving</th>
                    <th>Foto</th>
                    <th>Prijs Per Stuk</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
            <?php while($product = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['code']); ?></td>
                    <td><?php echo htmlspecialchars($product['omschrijving']); ?></td>
                    <td>
                        <?php if (!empty($product['foto'])): ?>
                            <img src="<?php echo htmlspecialchars($product['foto']); ?>" alt="Foto" width="100">
                        <?php else: ?>
                            Geen foto
                        <?php endif; ?>
                    </td>
                    <td>&euro; <?php echo htmlspecialchars(number_format($product['prijsPerStuk'], 2)); ?></td>
                    <td>
                        <button><a href="product-edit.php?id=<?php echo $product['id']; ?>">Bewerken</a></button> |
                        <button><a href="product-delete.php?id=<?php echo $product['id']; ?>"
                        onclick="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">Verwijderen</a></button>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Geen producten beschikbaar.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>
