<?php
session_start();
include '../php/dbconnection.php';
include 'verify_admin_password.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['password'])) {
    $userId = intval($_POST['id']);
    $adminPassword = $_POST['password'];

    if (verify_admin_password($adminPassword, $conn)) {
        // Prepare and execute the SQL statement
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            header("Location: ../php/dashboard.php");
        } else {
            echo "Error deleting record: " . $stmt->error;
        }
    } else {
        echo "Incorrect password";
    }
}

$conn->close();
?>
