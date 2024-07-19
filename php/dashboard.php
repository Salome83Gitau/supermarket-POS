<?php
session_start();
include '../php/dbconnection.php';

$companyName = "";
$companyLogo = "";
$sql = "SELECT company_name, logo FROM company WHERE company_id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $companyName = $row['company_name'];
    $companyLogo = base64_encode($row['logo']); 
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
</head>
<body>
    <div class="Dashboardwrapper">
        <div class="sidebar">
            <div class="company-info">
                <?php if ($companyLogo): ?>
                    <img src="data:image/png;base64,<?php echo $companyLogo; ?>" alt="Company Logo" height="50">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($companyName); ?></h2>
            </div>
            <p><a href="dashboard.php">Dashboard</a></p>
            <p><a href="stores.php">Stores</a></p>
            <p><a href="users.php">Users</a></p>
            <p><a href="suppliers.php">Suppliers</a></p>
            <p><a href="category.php">Category</a></p>
            <p><a href="products.php">Products</a></p>
            <p><a href="barcode_scanner.php">Barcode Scanner</a></p>
            <p><a href="reports.php">Reports</a></p>
            <p><a href="creditors.php">Creditors</a></p>
            <p><a href="expired.php" class="expired">Expired</a></p>
            <p><a href="logout.php">Logout</a></p>
        </div>
        <div class="dashboard">
         
                <h3 class="dashboard-header">Dashboard</h3>
            
            <div class="dashboard-content">
                <div class="sales">
                    <p>Today's sales</p>
                    <a href="#"><p>view</p></a>
                </div>
                <div class="expired">
                    <p>Expired</p>
                    <a href="#"><p>view</p></a>
                </div>
                <div class="invoice">
                    <p>Today's invoices</p>
                    <a href="#"><p>view</p></a>
                </div>
                <div class="newProducts">
                    <p>New Products</p>
                    <a href="#"><p>view</p></a>
                </div>
                <div class="suppliers"><p>Suppliers</p></div>
                <div class="invoices"><p>Invoices</p></div>
                <div class="currentmonthinvoices"><p>Invoices</p></div>
                <div class="last3monthsinvoices"><p>Last 3 months invoices</p></div>
                <div class="last6monthsinvoices"><p>Last 6 months invoices</p></div>
                <div class="users"><p>Users</p></div>
                <div class="availableproducts"><p>Available products</p></div>
                <div class="yearlyrevenue"><p>Yearly revenue</p></div>
                <div class="stores"><p>Stores</p></div>
            </div>
        </div>
    </div>
</body>
</html>
