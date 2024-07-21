<?php
include '../php/dbconnection.php';
include '../functions/sanitize.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST["Username"]);
    $password = test_input($_POST["password"]);
    $role = test_input($_POST["role"]);

    $sql = "SELECT * FROM users WHERE username='$username' AND role='$role'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            if ($role == "Admin") {
                header("Location: dashboard.php");
            } elseif ($role == "Cashier") {
                header("Location: cashiersDashboard.php");
            }
            exit();
        } else {
            $error_message = "Invalid password";
        }
    } else {
        $error_message = "Invalid username or role";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="wrapper">
        <div id="container">
            <p id="container-info">POS (point of sales) v3</p>
        </div>
        <div id="Company-setup">
            <h1>Login</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <br><br><br>
                <div id="form-group">
                    <input type="text" name="Username" id="Username" placeholder=" " required>
                    <label for="Username">Username</label>
                </div><br>
                <div id="form-group">
                    <input type="password" name="password" id="password" placeholder=" " required>
                    <label for="password">Password</label>
                </div>
                <div id="Select">
                    <p>Select role</p>
                    <label><input type="radio" name="role" value="Admin"> Admin</label>
                    <label><input type="radio" name="role" value="Cashier"> Cashier</label>
                </div>
                <br>
                <?php
                if (isset($error_message)) {
                    echo "<div class='error-message'>$error_message</div>";
                }
                ?>
                <div id="btn">
                    <button type="submit">Sign up</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
