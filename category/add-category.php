<?php
include '../php/dbconnection.php';

$name = $_POST['name'];
$description = $_POST['description'];

// Check if the category name already exists
$sql = "SELECT * FROM category WHERE category_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Category name already exists.']);
} else {
    $sql = "INSERT INTO category (category_name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $description);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Category added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add category.']);
    }
}

$conn->close();
?>
