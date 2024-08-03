<?php
include '../php/dbconnection.php';

$supplier_id = $_GET['id'];

$sql = "SELECT supplier_id, supplier_name, email, phone, product_name FROM supplier WHERE supplier_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['status' => 'error', 'message' => 'Supplier not found.']);
}

$conn->close();
?>
