<?php
session_start();
include '../php/dbconnection.php';
include '../functions/sanitize.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST["username"]);
    $name = test_input($_POST["name"]);
    $email = test_input($_POST["email"]);
    $password = password_hash(test_input($_POST["password"]), PASSWORD_DEFAULT);
    $role = test_input($_POST["role"]);

    // Check if email already exists
    $sql = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already exists. Please use a different email.'); window.location.href='../users/users.php';</script>";
    } else {
        $sql = "INSERT INTO users (username, name, email, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $name, $email, $password, $role);

        if ($stmt->execute()) {
            echo "<script>alert('User added successfully.'); window.location.href='../users/users.php';</script>";
        } else {
            echo "<script>alert('Error adding user.'); window.location.href='../users/users.php';</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
