<?php
include '../php/dbconnection.php';
include '../functions/sanitize.php';

$id =test_input($_POST['id']) ;
$username = test_input($_POST['username']);
$name = test_input($_POST['name']);
$email = test_input($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$role = test_input($_POST['role']);

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
