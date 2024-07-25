<?php
include '../php/dbconnection.php';

$id = $_POST['id'];

$sql = "DELETE FROM category WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Category deleted successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete category.']);
}

$conn->close();
?>
