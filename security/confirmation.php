<?php
session_start();
include '../php/dbconnection.php';
include '../functions/sanitize.php';

// Response array
$response = array('success' => false, 'message' => '');

// Check if request is AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['password'])) {
    $userId = test_input($_POST['user_id']);
    $username = test_input($_POST['username']);
    $name = test_input($_POST['name']);
    $email = test_input($_POST['email']);
    $role = test_input($_POST['role']);
    $password = test_input($_POST['password']);

    // Get admin id from session
    $adminId = $_SESSION['admin_id']; 

    // Verify admin password
    $sql = "SELECT password FROM users WHERE id='$adminId' AND role='Admin'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            // Password is correct, proceed with update
            $updateSql = "UPDATE users SET username='$username', name='$name', email='$email', role='$role' WHERE id='$userId'";
            if ($conn->query($updateSql) === TRUE) {
                $response['success'] = true;
                $response['message'] = 'User updated successfully.';
            } else {
                $response['message'] = 'Error updating record: ' . $conn->error;
            }
        } else {
            $response['message'] = 'Invalid password';
        }
    } else {
        $response['message'] = 'Admin not found';
    }
    $conn->close();
} else {
    $response['message'] = 'Invalid request';
}

// Return JSON response
echo json_encode($response);
?>
