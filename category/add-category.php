<?php
// Include the database connection file
include '../php/dbconnection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input values
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    // Check if the required fields are not empty
    if (!empty($name) && !empty($description)) {
        // Prepare the SQL statement to check if the category already exists (case-insensitive)
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM category WHERE LOWER(category_name) = LOWER(?)");
        $checkStmt->bind_param("s", $name);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            echo "Error: Category already exists."; // Provide feedback if category exists
        } else {
            // Prepare the SQL statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO category (category_name, description) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $description);

            // Execute the statement
            if ($stmt->execute()) {
                echo "success"; // Indicate successful insertion
            } else {
                echo "Error: " . $stmt->error; // Output any error that occurs
            }

            // Close the statement
            $stmt->close();
        }
    } else {
        echo "Error: Name and description are required."; // Provide feedback if fields are empty
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Error: Invalid request."; // Provide feedback if request method is not POST
}
?>
