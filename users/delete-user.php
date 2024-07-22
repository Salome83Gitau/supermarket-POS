<?php
session_start();
include '../php/dbconnection.php';

// Check if an ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = intval($_GET['id']);

    // Prepare SQL statement for deletion
    $sql = "DELETE FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            // Close the statement and connection
            $stmt->close();
            $conn->close();
            // Return success message
            echo "success";
        } else {
            // Handle execution error
            echo "Error executing statement: " . htmlspecialchars($stmt->error);
        }
    } else {
        // Handle preparation error
        echo "Error preparing statement: " . htmlspecialchars($conn->error);
    }
} else {
    echo "Invalid request";
}
?>
