<?php
session_start();
include '../php/dbconnection.php';

// Check if a category ID is provided
if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    $categoryId = intval($_GET['category_id']);

    // Prepare SQL statement for deletion
    $sql = "DELETE FROM category WHERE category_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("i", $categoryId);

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
