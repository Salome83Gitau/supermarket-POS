<?php
include '../php/dbconnection.php'; // Ensure this file includes your database connection logic

// Fetch company information
$companyName = "";
$companyLogo = "";
$sql = "SELECT company_name, logo FROM company WHERE company_id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $companyName = $row['company_name'];
    $companyLogo = base64_encode($row['logo']);
}

// Fetch product information
$productsData = [];
$sql = "
    SELECT p.product_id, p.product_name, p.price, p.cost, p.stock_quantity, p.expiration_date, p.barcode,
           c.category_name, s.supplier_name
    FROM product p
    JOIN category c ON p.category_id = c.category_id
    JOIN supplier s ON p.supplier_id = s.supplier_id
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productsData[] = $row;
    }
}

// Fetch categories
$categories = [];
$sql = "SELECT category_id, category_name FROM category";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Fetch suppliers
$suppliers = [];
$sql = "SELECT supplier_id, supplier_name FROM supplier";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Add your custom styles here */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 500px;
        }
        .popup-content button {
            margin-top: 10px;
        }
        .actions a {
            margin-right: 10px;
        }
    </style>
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
            <p><a href="../cashier/cashier-dashboard.php">Dashboard</a></p>
            <p><a href="../cashier/category.php">Category</a></p>
            <p><a href="#">Products</a></p>
            <p><a href="barcode_scanner.php">Barcode Scanner</a></p>
            <p><a href="../reports/reports.php">Reports</a></p>
            <p><a href="POS" >POS</a></p>
            <p><a href="credit">Credit sales</a></p> <br>
            <p><a href="../logout/logout.php" style="bottom: 10px;">Logout</a></p>
        </div>
        <div class="dashboard">
            <h3 class="dashboard-header">Products</h3>
            <div><p>Product Management</p></div>
            <div style="overflow-x: auto;">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Supplier</th>
                        <th>Price</th>
                        <th>Cost</th>
                        <th>Stock Quantity</th>
                        <th>Expiration Date</th>
                        <th>Barcode</th>
                    </tr>
                    <?php foreach ($productsData as $product) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['supplier_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                            <td><?php echo htmlspecialchars($product['cost']); ?></td>
                            <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                            <td><?php echo htmlspecialchars($product['expiration_date']); ?></td>
                            <td><?php echo htmlspecialchars($product['barcode']); ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
