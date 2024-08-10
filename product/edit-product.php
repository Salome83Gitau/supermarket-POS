<?php
include '../php/dbconnection.php';
include '../functions/sanitize.php';

// Retrieve and sanitize input
$id = test_input($_POST['product_id']);
$productName = test_input($_POST['product_name']);
$categoryId = test_input($_POST['category_id']);
$supplierId = test_input($_POST['supplier_id']);
$price = test_input($_POST['price']);
$cost = test_input($_POST['cost']);
$stockQuantity = test_input($_POST['stock_quantity']);
$expirationDate = test_input($_POST['expiration_date']);
$barcode = test_input($_POST['barcode']);

// Check if the product name already exists for a different ID
$sql = "SELECT * FROM product WHERE product_name = ? AND product_id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $productName, $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Product name already exists.']);
} else {
    $sql = "UPDATE product 
            SET product_name = ?, category_id = ?, supplier_id = ?, price = ?, cost = ?, stock_quantity = ?, expiration_date = ?, barcode = ?
            WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siidddssi", $productName, $categoryId, $supplierId, $price, $cost, $stockQuantity, $expirationDate, $barcode, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Product updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update product.']);
    }
}

$conn->close();
?>
