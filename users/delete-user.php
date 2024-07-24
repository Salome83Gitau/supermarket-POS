<?php
session_start();
include '../php/dbconnection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header('Location: users.php?message=User deleted successfully&type=success');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
