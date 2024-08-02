<?php
include '../php/dbconnection.php';

// Function to sanitize input data
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if the request is GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = (int)$_GET['id'];

    // Fetch user data
    $sql = "SELECT id, username, name, email, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(null);
    }

    $stmt->close();
    $conn->close();
}
?>
