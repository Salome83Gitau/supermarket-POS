<?php
session_start();
include '../php/dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $userId = intval($_POST['id']);
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    
    $sql = "UPDATE users SET username = ?, name = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $username, $name, $email, $role, $userId);
    
    if ($stmt->execute()) {
        header("Location: ../php/dashboard.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <form method="post" action="edit_user.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <label for="role">Role:</label>
            <input type="text" name="role" value="<?php echo htmlspecialchars($user['role']); ?>" required>
            <input type="submit" value="Update User">
        </form>
    </div>
</body>
</html>
