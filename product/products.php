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

$productData = [];
$sql = "SELECT 
            p.product_id, 
            p.product_name, 
            c.category_name, 
            s.supplier_name, 
            p.price, 
            p.cost, 
            p.stock_quantity, 
            p.expiration_date, 
            p.barcode 
        FROM product p
        JOIN category c ON p.category_id = c.category_id
        JOIN supplier s ON p.supplier_id = s.supplier_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productData[] = $row;
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
            <p><a href="../php/dashboard.php">Dashboard</a></p>
            <p><a href="stores.php">Stores</a></p>
            <p><a href="users.php">Users</a></p>
            <p><a href="suppliers.php">Suppliers</a></p>
            <p><a href="category.php">Category</a></p>
            <p><a href="../product/products.php">Products</a></p>
            <p><a href="barcode_scanner.php">Barcode Scanner</a></p>
            <p><a href="reports.php">Reports</a></p>
            <p><a href="expired.php" class="expired">Expired</a></p>
            <p><a href="creditors.php">Creditors</a></p> <br>
            <p><a href="logout.php">Logout</a></p>
        </div>
        <div class="dashboard">
            <div ><h3 class="dashboard-header">Products</h3></div>
            <div><p>Stock inventory</p></div>
            <div class="add-button"><button  >Add product</button></div>
            <div class="table">
                <table>
                    <tr>
                        <th>BARCODE</th>
                        <th>PRODUCT NAME</th>
                        <th>CATEGORY</th>
                        <th>SUPPLIER</th>
                        <th>PRICE</th>
                        <th>COST</th>
                        <th>INSTOCK</th>
                        <th>EXPIRE</th>
                    </tr>
                    <?php foreach ($productData as $product) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['barcode']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['supplier_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                            <td><?php echo htmlspecialchars($product['cost']); ?></td>
                            <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                            <td><?php echo htmlspecialchars($product['expiration_date']); ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
            <td><p>1</p></td>
            <td><p>milk</p></td>
            <td><p>dairy</p></td>
            <td><p>brookside</p></td>
            <td><p>100</p></td>
            <td><p>70</p></td>
            <td><p>10/10/1030</p></td>
            <td><p>1234</p></td>
            </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
