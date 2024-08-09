<?php
include '../php/dbconnection.php';

$product_id = $_POST['product_id'];

$sql = "DELETE FROM product WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete product.']);
}

$conn->close();
?>
