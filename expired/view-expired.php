<?php
session_start();
include '../php/dbconnection.php';
include 'check-expired.php';

$expiredProducts = getExpiredProducts($conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expired Products</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Expired Products</h2>
        <?php if (count($expiredProducts) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Expiration Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($expiredProducts as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['expiration_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No expired products found.</p>
        <?php endif; ?>
        <a href="../php/dashboard.php">Go back to dashboard</a>
    </div>
</body>
</html>
