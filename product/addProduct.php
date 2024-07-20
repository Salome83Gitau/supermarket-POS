<?php
session_start();
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $productName = $_POST['product_name'];
    $categoryId = $_POST['category_id'];
    $supplierId = $_POST['supplier_id'];
    $price = $_POST['price'];
    $cost = $_POST['cost'];
    $stockQuantity = $_POST['stock_quantity'];
    $expirationDate = $_POST['expiration_date'];
    $barcode = $_POST['barcode'];
    $password = $_POST['password'];
    $adminUsername = $_SESSION['username']; // Assuming username is stored in session

    // Verify admin password
    $sql = "SELECT password FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $adminUsername);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hash);
    $stmt->fetch();

    if (password_verify($password, $hash)) {
        // Insert product into the database
        $sql = "INSERT INTO product (product_name, category_id, supplier_id, price, cost, stock_quantity, expiration_date, barcode) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('siiddiss', $productName, $categoryId, $supplierId, $price, $cost, $stockQuantity, $expirationDate, $barcode);

        if ($stmt->execute()) {
            echo "Product added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Invalid password.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
