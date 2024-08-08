<?php
include '../php/dbconnection.php';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $supplier_id = $_POST['supplier_id'];
    $price = $_POST['price'];
    $cost = $_POST['cost'];
    $stock_quantity = $_POST['stock_quantity'];
    $expiration_date = $_POST['expiration_date'];
    $barcode = $_POST['barcode'];

    // Check for duplicates
    $stmt = $conn->prepare("SELECT COUNT(*) FROM product WHERE product_name = ?");
    $stmt->bind_param("s", $product_name);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "Product with this name already exists.";
    } else {
        // Add the product
        $stmt = $conn->prepare("INSERT INTO product (product_name, category_id, supplier_id, price, cost, stock_quantity, expiration_date, barcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siidddss", $product_name, $category_id, $supplier_id, $price, $cost, $stock_quantity, $expiration_date, $barcode);
        
        if ($stmt->execute()) {
            echo "Product added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
