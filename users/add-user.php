<?php
session_start();
include '../php/dbconnection.php';
include '../functions/sanitize.php'; // Include your sanitize function

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $username = test_input($_POST['username']);
    $name = test_input($_POST['name']);
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);
    $role = test_input($_POST['role']);

    // Check if email already exists
    $checkEmailSql = "SELECT id FROM users WHERE email = ?";
    if ($checkStmt = $conn->prepare($checkEmailSql)) {
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            // Email already exists
            echo "Error: Email is already taken.";
            $checkStmt->close();
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement for insertion
            $sql = "INSERT INTO users (username, name, email, password, role) VALUES (?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                // Bind parameters
                $stmt->bind_param("sssss", $username, $name, $email, $hashedPassword, $role);

                if ($stmt->execute()) {
                    // Close the statement and connection
                    $stmt->close();
                    $conn->close();
                    // Redirect or display success message
                    header("Location: users.php?message=User added successfully");
                    exit(); // Ensure no further code is executed
                } else {
                    // Handle execution error
                    echo "Error executing statement: " . htmlspecialchars($stmt->error);
                }
            } else {
                // Handle preparation error
                echo "Error preparing statement: " . htmlspecialchars($conn->error);
            }
        }
    } else {
        // Handle check preparation error
        echo "Error preparing check statement: " . htmlspecialchars($conn->error);
    }
}
?>
