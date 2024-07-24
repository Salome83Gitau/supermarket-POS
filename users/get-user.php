<?php
session_start();
include '../php/dbconnection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT id, username, name, email, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo json_encode($user);
        } else {
            echo json_encode([]);
        }
    } else {
        echo json_encode([]);
    }

    $stmt->close();
    $conn->close();
}
?>
