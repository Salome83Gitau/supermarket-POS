<?php
session_start();
include '../php/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Check if email already exists for a different user
    $sql = "SELECT * FROM users WHERE email = ? AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $email, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header('Location: users.php?message=Email already exists&type=error');
    } else {
        $sql = "UPDATE users SET username = ?, name = ?, email = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $username, $name, $email, $role, $id);

        if ($stmt->execute()) {
            header('Location: users.php?message=User updated successfully&type=success');
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>
