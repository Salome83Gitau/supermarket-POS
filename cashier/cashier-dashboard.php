<?php
session_start();
include '../php/dbconnection.php';
include '../functions/count.php';

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
            <p><a href="#">Dashboard</a></p>
            <p><a href="../category/category.php">Category</a></p>
            <p><a href="../product/products.php">Products</a></p>
            <p><a href="barcode_scanner.php">Barcode Scanner</a></p>
            <p><a href="../reports/reports.php">Reports</a></p>
            <p><a href="POS" >POS</a></p>
            <p><a href="credit">Credit sales</a></p> <br>
            <p><a href="logout.php" style="bottom: 10px;">Logout</a></p>
        </div>
        <div class="dashboard">
            <div class="dashboard-header"><h3 class="dashboard-header">Dashboard</h3>
            </div>
            
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
            </div>
            <div class="subdashboardcontent">
                <div class="suppliers"><p>Suppliers</p>
                <b><?php echo $supplierCount;?></b>
            
            </div>
                <div class="invoices"><p>Invoices</p></div>
                <div class="currentmonthinvoices"><p>Current month Invoices</p></div>
                <div class="last3monthsinvoices"><p>Last 3 months invoices</p></div>
                <div class="last6monthsinvoices"><p>Last 6 months invoices</p></div>
                <div class="users"><p>Users</p><b><?php echo $userCount;?></b></div>
                <div class="availableproducts"><p>Available products</p><b><?php echo $productCount;?></b></div>
                <div class="yearlyrevenue"><p>Yearly revenue</p></div>
                <div class="stores"><p>Stores</p> <b><?php echo $storeCount;?></b> </div>
            </div>
        </div>
    </div>
</body>
</html>
