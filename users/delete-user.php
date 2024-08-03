<?php
include '../php/dbconnection.php';

$id = $_POST['id'];

$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete user.']);
}

$conn->close();
?>
