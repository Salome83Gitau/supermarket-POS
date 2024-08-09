<?php
include '../php/dbconnection.php';
include '../functions/sanitize.php';

$product_name = test_input($_POST['product_name']);
$category_id = test_input($_POST['category_id']);
$supplier_id = test_input($_POST['supplier_id']);
$price = test_input($_POST['price']);
$cost = test_input($_POST['cost']);
$stock_quantity = test_input($_POST['stock_quantity']);
$expiration_date = test_input($_POST['expiration_date']);
$barcode = test_input($_POST['barcode']);

// Check if the product name already exists
$sql = "SELECT * FROM product WHERE product_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $product_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Product name already exists.']);
} else {
    $sql = "INSERT INTO product (product_name, category_id, supplier_id, price, cost, stock_quantity, expiration_date, barcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siidddss", $product_name, $category_id, $supplier_id, $price, $cost, $stock_quantity, $expiration_date, $barcode);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Product added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add product.']);
    }
}

$conn->close();
?>
