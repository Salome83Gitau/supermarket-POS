<?php
include '../php/dbconnection.php';

$supplier_id = $_POST['supplier_id'];
$supplier_name = $_POST['supplier_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$product_name = $_POST['product_name'];

// Check if the email already exists for a different ID
$sql = "SELECT * FROM supplier WHERE email = ? AND supplier_id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $email, $supplier_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
} else {
    $sql = "UPDATE supplier SET supplier_name = ?, email = ?, phone = ?, product_name = ? WHERE supplier_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $supplier_name, $email, $phone, $product_name, $supplier_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Supplier updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update supplier.']);
    }
}

$conn->close();
?>
