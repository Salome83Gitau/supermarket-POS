<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="typewriter.js"></script>
</head>
<body>
    <div class="wrapper">
        <div id="container">
            <p id="container-info">POS (point of sales) v3</p>
        </div>
        <div id="Company-setup">
            <h1>Login</h1>
            <form action=" method="post">
                 <br> <br> <br>
                <div id="form-group">
                    <input type="text" name="Username" id="Username" placeholder=" " required>
                    <label for="Username">Username</label>
                </div> <br>
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
                <div id="btn">
                    <button type="submit">Sign up</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> -->
<?php
include '../functions/sanitize.php';
include '../php/db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST['Username']);
    $password = test_input($_POST['password']);
    $role = test_input($_POST['role']);
    
    $sql = "SELECT * FROM admin WHERE username = ? AND role = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                header("Location: dashboard.php"); // Redirect to the admin dashboard
                exit;
            } else {
                echo "Invalid password";
            }
        } else {
            echo "No user found with the provided credentials";
        }
        
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="typewriter.js"></script>
</head>
<body>
    <div class="wrapper">
        <div id="container">
            <p id="container-info">POS (point of sales) v3</p>
        </div>
        <div id="Company-setup">
            <h1>Login</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div id="form-group">
                    <input type="text" name="Username" id="Username" placeholder=" " required>
                    <label for="Username">Username</label>
                </div>
                <div id="form-group">
                    <input type="password" name="password" id="password" placeholder=" " required>
                    <label for="password">Password</label>
                </div>
                <div id="Select">
                    <p>Select role</p>
                    <label><input type="radio" name="role" value="Admin"> Admin</label>
                    <label><input type="radio" name="role" value="Cashier"> Cashier</label>
                </div>
                <div id="btn">
                    <button type="submit">Sign up</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

