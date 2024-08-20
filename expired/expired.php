<?php
include '../php/dbconnection.php';

// Fetch company details
$companyName = "";
$companyLogo = "";
$sql = "SELECT company_name, logo FROM company WHERE company_id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $companyName = $row['company_name'];
    $companyLogo = base64_encode($row['logo']);
}

// Fetch expired products
$today = date('Y-m-d');
$expiredProducts = [];
$sql = "SELECT product_id, product_name, expiration_date FROM product WHERE expiration_date < ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $today);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $expiredProducts[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/addCategory.js"></script>
</head>
<body>
    <div class="Dashboardwrapper">
        <div class="sidebar">
            <div class="company-info">
                <?php if ($companyLogo): ?>
                    <img src="data:image/png;base64,<?php echo $companyLogo; ?>" alt="Company Logo" height="50" id="logo">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($companyName); ?></h2>
            </div>
            <p><a href="../php/dashboard.php">Dashboard</a></p>
            <p><a href="../stores/stores.php">Stores</a></p>
            <p><a href="../users/users.php">Users</a></p>
            <p><a href="../suppliers/suppliers.php">Suppliers</a></p>
            <p><a href="../category/category.php">Category</a></p>
            <p><a href="../product/products.php">Products</a></p>
            <p><a href="barcode_scanner.php">Barcode Scanner</a></p>
            <p><a href="../reports/reports.php">Reports</a></p>
            <p><a href="../expired/view-expired.php" class="expired">Expired</a></p>
            <p><a href="../creditors/creditors.php">Creditors</a></p> <br>
            <p><a href="../logout/logout.php">Logout</a></p>
        </div>
        <div class="dashboard">
            <div><h3 class="dashboard-header" style="color: red;">Expired Products</h3></div>
            
            <div>
                <?php if (count($expiredProducts) > 0): ?>
                    <p style="color: red;">You have expired products!</p>
                    <a href="../expired/view-expired.php"><p style="color: blue;">View Expired Products</p></a>
                <?php else: ?>
                    <p>No expired products found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
