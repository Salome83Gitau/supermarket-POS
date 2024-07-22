<?php
session_start();
include '../php/dbconnection.php';

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    
    $sql = "SELECT id, username, name, email, role FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(["error" => "No user found"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["error" => "Error preparing statement: " . htmlspecialchars($conn->error)]);
    }
    $conn->close();
} else {
    echo json_encode(["error" => "No ID provided"]);
}
?>
