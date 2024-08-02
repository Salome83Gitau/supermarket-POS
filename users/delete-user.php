<?php
include '../php/dbconnection.php';

// Function to sanitize input data
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if the request is GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = (int)$_GET['id'];

    // Delete user
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting user.']);
    }

    $stmt->close();
    $conn->close();
}
?>
