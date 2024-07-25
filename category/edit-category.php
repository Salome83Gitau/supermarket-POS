<?php
include '../php/dbconnection.php';

$id = $_POST['id'];
$name = $_POST['name'];
$description = $_POST['description'];

// Check if the category name already exists for a different ID
$sql = "SELECT * FROM category WHERE category_name = ? AND category_id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $name, $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Category name already exists.']);
} else {
    $sql = "UPDATE category SET category_name = ?, description = ? WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $description, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Category updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update category.']);
    }
}

$conn->close();
?>
