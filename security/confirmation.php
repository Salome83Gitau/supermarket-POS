<?php
// session_start();
include '../php/dbconnection.php';
// include '../functions/sanitize.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['user_id']) && isset($_POST['password'])) {
    $userId = test_input($_POST['user_id']);
    $action = test_input($_POST['action']);
    $password = test_input($_POST['password']);

    $adminId = $_SESSION['admin_id']; // Assuming admin's ID is stored in session
    $sql = "SELECT password FROM users WHERE id='$adminId' AND role='Admin'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            if ($action == 'edit') {
                header("Location: edit-user.php?id=$userId");
                exit();
            } elseif ($action == 'delete') {
                header("Location: delete-user.php?id=$userId");
                exit();
            }
        } else {
            $error_message = "Invalid password";
        }
    } else {
        $error_message = "Admin not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="confirmation-form">
        <h2>Enter Password for Confirmation</h2>
        <form action="confirmation.php" method="post">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo isset($_POST['user_id']) ? htmlspecialchars($_POST['user_id']) : ''; ?>">
            <input type="hidden" name="action" id="action" value="<?php echo isset($_POST['action']) ? htmlspecialchars($_POST['action']) : ''; ?>">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Confirm</button>
        </form>
        <?php if (!empty($error_message)) { ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php } ?>
    </div>
</body>
</html>
