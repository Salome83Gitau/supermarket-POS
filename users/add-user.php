<?php
include '../php/dbconnection.php';

// Function to sanitize input data
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted data
    $username = test_input($_POST['username']);
    $name = test_input($_POST['name']);
    $password = password_hash(test_input($_POST['password']), PASSWORD_BCRYPT);
    $email = test_input($_POST['email']);
    $role = test_input($_POST['role']);

    // Check for duplicate email
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        echo json_encode(['success' => false, 'message' => 'Email already exists.']);
    } else {
        // Insert new user
        $sql = "INSERT INTO users (username, name, password, email, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $name, $password, $email, $role);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User added successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error adding user.']);
        }
    }

    $stmt->close();
    $conn->close();
}
?>
