<?php
include '../php/dbconnection.php';

$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$category_id = $_POST['category_id'];
$supplier_id = $_POST['supplier_id'];
$price = $_POST['price'];
$cost = $_POST['cost'];
$stock_quantity = $_POST['stock_quantity'];
$expiration_date = $_POST['expiration_date'];
$barcode = $_POST['barcode'];

// Check for duplicate product name
$sql = "SELECT * FROM product WHERE product_name = ? AND product_id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $product_name, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Product name already exists."]);
    exit();
}

$sql = "UPDATE product SET product_name = ?, category_id = ?, supplier_id = ?, price = ?, cost = ?, stock_quantity = ?, expiration_date = ?, barcode = ?
        WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siiddissi", $product_name, $category_id, $supplier_id, $price, $cost, $stock_quantity, $expiration_date, $barcode, $product_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Product updated successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Error updating product: " . $stmt->error]);
}

$conn->close();
?>
