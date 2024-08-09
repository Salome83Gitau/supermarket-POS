<?php
include '../php/dbconnection.php';

$productId = intval($_GET['id']);
$sql = "SELECT p.product_id, p.product_name, p.category_id, p.supplier_id, p.price, p.cost, p.stock_quantity, p.expiration_date, p.barcode
        FROM product p
        WHERE p.product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode([]);
}

$conn->close();
?>
