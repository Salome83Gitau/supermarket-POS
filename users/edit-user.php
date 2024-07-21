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
        exit(); // Ensure no further code execution after redirect
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
    <style>
        /* Existing CSS styles */
        .open-button {
            background-color: #555;
            color: white;
            padding: 16px 20px;
            border: none;
            cursor: pointer;
            opacity: 0.8;
            position: fixed;
            bottom: 23px;
            right: 28px;
            width: 280px;
        }

        /* The popup form - hidden by default */
        .form-popup {
            display: none; /* Hide the popup by default */
            position: fixed;
            bottom: 0;
            right: 15px;
            border: 3px solid #f1f1f1;
            z-index: 9;
            width: 300px; /* Adjust width as needed */
        }

        /* Add styles to the form container */
        .form-container {
            max-width: 300px;
            padding: 10px;
            background-color: white;
        }

        /* Full-width input fields */
        .form-container input[type=text], .form-container input[type=password] {
            width: 100%;
            padding: 15px;
            margin: 5px 0 22px 0;
            border: none;
            background: #f1f1f1;
        }

        /* When the inputs get focus, do something */
        .form-container input[type=text]:focus, .form-container input[type=password]:focus {
            background-color: #ddd;
            outline: none;
        }

        /* Set a style for the submit/login button */
        .form-container .btn {
            background-color: #04AA6D;
            color: white;
            padding: 16px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-bottom: 10px;
            opacity: 0.8;
        }

        /* Add a red background color to the cancel button */
        .form-container .cancel {
            background-color: red;
        }

        /* Add some hover effects to buttons */
        .form-container .btn:hover, .open-button:hover {
            opacity: 1;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="edit-user-form">
        <h2>Edit User</h2>
        <form id="userForm">
            <input type="hidden" name="user_id" value="<?php echo isset($user['id']) ? htmlspecialchars($user['id']) : ''; ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo isset($user['username']) ? htmlspecialchars($user['username']) : ''; ?>" required>
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : ''; ?>" required>
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" required>
            <label for="role">Role:</label>
            <select name="role" required>
                <option value="Admin" <?php echo isset($user['role']) && $user['role'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="Cashier" <?php echo isset($user['role']) && $user['role'] == 'Cashier' ? 'selected' : ''; ?>>Cashier</option>
            </select>
            <button type="button" class="open-button" onclick="openForm()">Update User</button>
        </form>
    </div>

    <!-- The Popup Form -->
    <div class="form-popup" id="myForm">
        <form class="form-container" id="confirmationForm">
            <h1>Confirm Action</h1>
            <input type="hidden" name="user_id" id="popup_user_id">
            <input type="hidden" name="username" id="popup_username">
            <input type="hidden" name="name" id="popup_name">
            <input type="hidden" name="email" id="popup_email">
            <input type="hidden" name="role" id="popup_role">
            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" id="popup_password" required>
            <button type="submit" class="btn">Confirm</button>
            <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
            <p class="error-message" id="error_message"></p>
        </form>
    </div>

    <script>
        function openForm() {
            // Populate hidden fields in the popup form
            var form = document.getElementById('userForm');
            document.getElementById('popup_user_id').value = form.querySelector("input[name='user_id']").value;
            document.getElementById('popup_username').value = form.querySelector("input[name='username']").value;
            document.getElementById('popup_name').value = form.querySelector("input[name='name']").value;
            document.getElementById('popup_email').value = form.querySelector("input[name='email']").value;
            document.getElementById('popup_role').value = form.querySelector("select[name='role']").value;

            // Show the popup
            document.getElementById("myForm").style.display = "block";
        }

        function closeForm() {
            // Hide the popup
            document.getElementById("myForm").style.display = "none";
        }

        document.getElementById('confirmationForm').onsubmit = function(event) {
            event.preventDefault(); // Prevent default form submission

            var userId = document.getElementById('popup_user_id').value;
            var username = document.getElementById('popup_username').value;
            var name = document.getElementById('popup_name').value;
            var email = document.getElementById('popup_email').value;
            var role = document.getElementById('popup_role').value;
            var password = document.getElementById('popup_password').value;

            // Create a new FormData object
            var formData = new FormData();
            formData.append('user_id', userId);
            formData.append('username', username);
            formData.append('name', name);
            formData.append('email', email);
            formData.append('role', role);
            formData.append('password', password);

            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'confirmation.php', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // If the password is correct and update is successful, close the popup
                        closeForm();
                        window.location.href = '../php/dashboard.php'; // Redirect or handle success as needed
                    } else {
                        // Show the error message
                        document.getElementById('error_message').innerText = response.message;
                    }
                } else {
                    document.getElementById('error_message').innerText = 'An error occurred. Please try again.';
                }
            };

            xhr.send(formData);
        }
    </script>
</body>
</html>
