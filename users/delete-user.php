<?php
session_start();
include '../php/dbconnection.php';
include '../functions/sanitize.php';
include '../security/confirmation.php';

if (isset($_GET['id'])) {
    $userId = test_input($_GET['id']);
    $sql = "DELETE FROM users WHERE id='$userId'";
    if ($conn->query($sql) === TRUE) {
        header("Location: ../php/dashboard.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $conn->close();
} else {
    echo "No user ID provided";
}
?>
