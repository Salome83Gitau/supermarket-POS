<?php
include '../php/dbconnection.php';

$id = $_GET['id'];

// Prepare and execute the query to fetch product details
$sql = "SELECT p.product_id, p.product_name, p.category_id, p.supplier_id, p.price, p.cost, p.stock_quantity, p.expiration_date, p.barcode,
               c.category_name, s.supplier_name
        FROM product p
        JOIN category c ON p.category_id = c.category_id
        JOIN supplier s ON p.supplier_id = s.supplier_id
        WHERE p.product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Return the product details as JSON
if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
}

$conn->close();
?>
