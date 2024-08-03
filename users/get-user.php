<?php
include '../php/dbconnection.php';

$id = $_GET['id'];

$sql = "SELECT id, username, name, email, password, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found.']);
}

$conn->close();
?>
