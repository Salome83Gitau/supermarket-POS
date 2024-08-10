<?php
include '../php/dbconnection.php';

// Database connection code
$categoryName = "";
$companyLogo = "";
$sql = "SELECT company_name, logo FROM company WHERE company_id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $categoryName = $row['company_name'];
    $companyLogo = base64_encode($row['logo']);
}

$categoryData = [];
$sql = "SELECT category_id, category_name, description FROM category";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categoryData[] = $row;
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
                <h2><?php echo htmlspecialchars($categoryName); ?></h2>
            </div>
            <p><a href="../php/dashboard.php">Dashboard</a></p>
            <p><a href="../stores/stores.php">Stores</a></p>
            <p><a href="../users/users.php">Users</a></p>
            <p><a href="../suppliers/suppliers.php">Suppliers</a></p>
            <p><a href="../category/category.php">Category</a></p>
            <p><a href="../product/products.php">Products</a></p>
            <p><a href="barcode_scanner.php">Barcode Scanner</a></p>
            <p><a href="../reports/reports.php">Reports</a></p>
            <p><a href="#" class="expired">Expired</a></p>
            <p><a href="../creditors/creditors.php">Creditors</a></p> <br>
            <p><a href="../logout/logout.php">Logout</a></p>
        </div>
        <div class="dashboard">
            <div><h3 class="dashboard-header" style="color: red;">Expired</h3></div>
            <div><p style="color: red;">Expiration notification</p></div>
           </body>
</html>
