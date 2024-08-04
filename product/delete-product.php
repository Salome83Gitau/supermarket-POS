<?php
include '../php/dbconnection.php';

$product_id = $_GET['id'];

$sql = "DELETE FROM product WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Product deleted successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Error deleting product: " . $stmt->error]);
}

$conn->close();
?>
