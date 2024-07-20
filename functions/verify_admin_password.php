<?php
function verify_admin_password($adminPassword, $conn) {
    session_start();
    $adminId = $_SESSION['admin_id']; // Assumes admin ID is stored in session

    // Prepare and execute the SQL statement
    $sql = "SELECT password FROM admins WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $adminId);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return false; // Admin not found
    }

    $admin = $result->fetch_assoc();

    // Verify the password
    return password_verify($adminPassword, $admin['password']);
}
?>
