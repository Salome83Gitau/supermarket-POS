<?php
include '../php/dbconnection.php'; // Ensure this file includes your database connection logic
include '../functions/sanitize.php'; // Ensure this file includes your sanitization functions

// Retrieve and sanitize input
$productName = test_input($_POST['product_name']);
$categoryId = test_input($_POST['category_id']);
$supplierId = test_input($_POST['supplier_id']);
$price = test_input($_POST['price']);
$cost = test_input($_POST['cost']);
$stockQuantity = test_input($_POST['stock_quantity']);
$expirationDate = test_input($_POST['expiration_date']);
$barcode = test_input($_POST['barcode']);

// Check for duplicate product
$sql = "SELECT * FROM product WHERE product_name = ? AND category_id = ? AND supplier_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $productName, $categoryId, $supplierId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Product with this name already exists in the selected category and supplier.']);
} else {
    // Insert new product
    $sql = "INSERT INTO product (product_name, category_id, supplier_id, price, cost, stock_quantity, expiration_date, barcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $productName, $categoryId, $supplierId, $price, $cost, $stockQuantity, $expirationDate, $barcode);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Product added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add product.']);
    }
}

$conn->close();
?>
