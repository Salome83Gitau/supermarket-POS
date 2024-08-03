<?php
include 'dbconnection.php';
include 'sanitize.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = test_input($_POST['username']);
    $name = test_input($_POST['name']);
    $password = password_hash(test_input($_POST['password']), PASSWORD_DEFAULT);
    $email = test_input($_POST['email']);
    $role = test_input($_POST['role']);

    // Check for duplicates
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = 'User with this email already exists.';
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, name, password, email, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $name, $password, $email, $role);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'User added successfully.';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to add user.';
        }
        $stmt->close();
    }

    $conn->close();
    echo json_encode($response);
}
?>
