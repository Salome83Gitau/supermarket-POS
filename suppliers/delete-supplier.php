<?php
include '../php/dbconnection.php';

$supplier_id = $_POST['supplier_id'];

$sql = "DELETE FROM supplier WHERE supplier_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $supplier_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Supplier deleted successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete supplier.']);
}

$conn->close();
?>
