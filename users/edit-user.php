<?php
session_start();
include '../php/dbconnection.php';
include '../functions/sanitize.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $userId = test_input($_POST['user_id']);
    $username = test_input($_POST['username']);
    $name = test_input($_POST['name']);
    $email = test_input($_POST['email']);
    $role = test_input($_POST['role']);

    $sql = "UPDATE users SET username='$username', name='$name', email='$email', role='$role' WHERE id='$userId'";
    if ($conn->query($sql) === TRUE) {
        header("Location: ../php/dashboard.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $conn->close();
} else {
    if (isset($_GET['id'])) {
        $userId = test_input($_GET['id']);
        $sql = "SELECT * FROM users WHERE id='$userId'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
        } else {
            echo "No user found";
        }
    }
}
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
        <div class="edit-user-form">
            <h2>Edit User</h2>
            <form action="edit-user.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            <label for="role">Role:</label>
            <select name="role" required>
                <option value="Admin" <?php if ($user['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
                <option value="Cashier" <?php if ($user['role'] == 'Cashier') echo 'selected'; ?>>Cashier</option>
            </select>
            <button type="submit" >Update User</button>
        </form>
    </div>
</body>
</html>
