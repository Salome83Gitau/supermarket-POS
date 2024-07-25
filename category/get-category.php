<?php
include '../php/dbconnection.php';

$id = $_GET['id'];

$sql = "SELECT * FROM category WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['status' => 'error', 'message' => 'Category not found.']);
}

$conn->close();
?>
