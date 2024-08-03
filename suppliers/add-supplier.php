<?php
include '../php/dbconnection.php';

$supplier_name = $_POST['supplier_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$product_name = $_POST['product_name'];

// Check if the email already exists
$sql = "SELECT * FROM supplier WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
} else {
    $sql = "INSERT INTO supplier (supplier_name, email, phone, product_name) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $supplier_name, $email, $phone, $product_name);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Supplier added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add supplier.']);
    }
}

$conn->close();
?>
