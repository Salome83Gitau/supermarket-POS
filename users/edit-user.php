<?php
include '../php/dbconnection.php';

$id = $_POST['id'];
$username = $_POST['username'];
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$role = $_POST['role'];

// Check if the email already exists for a different ID
$sql = "SELECT * FROM users WHERE email = ? AND id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
} else {
    $sql = "UPDATE users SET username = ?, name = ?, email = ?, password = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $username, $name, $email, $password, $role, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'User updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update user.']);
    }
}

$conn->close();
?>
