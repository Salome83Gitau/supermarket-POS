<?php
include '../php/dbconnection.php';

// Function to sanitize input data
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted data
    $id = (int)$_POST['id'];
    $username = test_input($_POST['username']);
    $name = test_input($_POST['name']);
    $email = test_input($_POST['email']);
    $role = test_input($_POST['role']);
    $password = !empty($_POST['password']) ? password_hash(test_input($_POST['password']), PASSWORD_BCRYPT) : null;

    // Check for duplicate email
    $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        echo json_encode(['success' => false, 'message' => 'Email already exists.']);
    } else {
        // Update user
        if ($password) {
            $sql = "UPDATE users SET username = ?, name = ?, password = ?, email = ?, role = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $username, $name, $password, $email, $role, $id);
        } else {
            $sql = "UPDATE users SET username = ?, name = ?, email = ?, role = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $username, $name, $email, $role, $id);
        }

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating user.']);
        }
    }

    $stmt->close();
    $conn->close();
}
?>
