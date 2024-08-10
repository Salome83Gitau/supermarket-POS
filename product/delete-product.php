<?php
include '../php/dbconnection.php'; // Ensure this file includes your database connection logic

// Retrieve and sanitize input
$id = test_input($_POST['id']);

// Prepare the SQL query
$sql = "DELETE FROM product WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

// Check if the deletion was successful
if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete product.']);
}

$conn->close();
?>
