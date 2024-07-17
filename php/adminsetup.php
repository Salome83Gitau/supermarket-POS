<?php
include 'dbconnection.php';
include 'sanitize.php';

$username = $name = $password = $email = $status = "";
$role = "admin";
$usernameErr = $nameErr = $passwordErr = $emailErr = $roleErr = $statusErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["Username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["Username"]);
    }

    if (empty($_POST["full_name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["full_name"]);
    }

    if (empty($_POST["Password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = password_hash(test_input($_POST["Password"]), PASSWORD_DEFAULT);
    }

    if (empty($_POST["Email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["Email"]);
    }

 

    if (empty($_POST["status"])) {
        $statusErr = "Status is required";
    } else {
        $status = test_input($_POST["status"]);
    }

    // Insert data into database
    if (empty($usernameErr) && empty($nameErr) && empty($passwordErr) && empty($emailErr) && empty($roleErr) && empty($statusErr)) {
        $stmt = $conn->prepare("INSERT INTO users (username, name, password, email, role, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $name, $password, $email, $role, $status);

        if ($stmt->execute()) {
            echo "New user created successfully";
            header("Location:../php/Product_activation.php");
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin setup</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="wrapper">
        <div id="container">
            <p id="container-info">POS (point of sales) v3</p>
        </div>
        <div id="Company-setup">
            <h1>Admin Account setup</h1>
            <!-- htmlspecialchars function converts special xters to HTML entities -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div id="form-group">
                    <input type="text" name="full_name" id="full_name" placeholder=" " required>
                    <label for="full_name">Full name</label>
                </div>
                <div id="form-group">
                    <input type="email" name="Email" id="Email" placeholder=" " required>
                    <label for="Email">Email</label>
                </div>
                <div id="form-group">
                    <input type="tel" name="Phone" id="Phone" placeholder=" " required>
                    <label for="Phone">Phone</label>
                </div>
                <div id="form-group">
                    <input type="text" name="Username" id="Username" placeholder=" " required>
                    <label for="Username">Username</label>
                </div>
                <div id="form-group">
                    <input type="password" name="Password" id="Password" placeholder=" " required>
                    <label for="Password">Password</label>
                </div>
                <div id="btn">
                    <button type="submit">SUBMIT & CONTINUE</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


