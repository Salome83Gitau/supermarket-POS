<?php
include '../php/dbconnection.php';
include '../functions/sanitize.php';

$username = test_input($_POST['username']);
$name = test_input($_POST['name']);
$email = test_input($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$role = test_input($_POST['role']);

// Check if the email already exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
} else {
    $sql = "INSERT INTO users (username, name, email, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $name, $email, $password, $role);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'User added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add user.']);
    }
}

$conn->close();
?>
