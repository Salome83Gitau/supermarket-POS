<?php
include '../php/dbconnection.php';

// Fetch company details
$companyName = "";
$companyLogo = "";
$sql = "SELECT company_name, logo FROM company WHERE company_id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $companyName = $row['company_name'];
    $companyLogo = base64_encode($row['logo']);
}

// Fetch user data
$userData = [];
$sql = "SELECT id, username, name, email, role FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userData[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Popup Styles */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            position: relative;
        }

        .popup-content h2 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .popup-content form {
            display: flex;
            flex-direction: column;
        }

        .popup-content label {
            margin-bottom: 5px;
        }

        .popup-content input {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .popup-content button {
            padding: 10px;
            border: none;
            border-radius: 3px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            margin-bottom: 5px;
        }

        .popup-content button.cancelBtn {
            background-color: #dc3545;
        }

        .popup-content button.cancelBtn:hover {
            background-color: #c82333;
        }

        .popup-content button.closeSuccessBtn {
            background: linear-gradient(to right, #5f3481, #b784d7);
        }

        .popup-content button.closeSuccessBtn:hover {
            background-color: #251742;
        }

        /* Success Popup Styles */
        #successPopup {
            display: none;
        }

        #successPopup h2 {
            margin-top: 0;
        }

        #successPopup #successMessage {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="Dashboardwrapper">
        <div class="sidebar">
            <div class="company-info">
                <?php if ($companyLogo): ?>
                    <img src="data:image/png;base64,<?php echo $companyLogo; ?>" alt="Company Logo" height="50" id="logo">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($companyName); ?></h2>
            </div>
            <p><a href="../php/dashboard.php">Dashboard</a></p>
            <!-- Other Sidebar Links Here -->
            <p><a href="logout.php">Logout</a></p>
        </div>
        <div class="dashboard">
            <div><h3 class="dashboard-header">Users</h3></div>
            <div><p>User Management</p></div>
            <div class="add-button"><button>Add User</button></div>
            <div style="overflow-x: auto;">
                <div class="table">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                        <tbody id="userTableBody">
                            <?php foreach ($userData as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="showEditUserPopup(<?php echo $user['id']; ?>)">Edit</a>
                                        <a href="javascript:void(0)" onclick="deleteUser(<?php echo $user['id']; ?>)">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Popup -->
    <div id="addUserPopup" class="popup">
        <div class="popup-content">
            <form id="addUserForm" method="post" action="add-user.php">
                <h2>Add User</h2>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Username" required>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Name" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <label for="role">Role:</label>
                <input type="text" id="role" name="role" placeholder="Role" required>
                <button type="submit">Add User</button>
                <button type="button" class="cancelBtn">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Edit User Popup -->
    <div id="editUserPopup" class="popup">
        <div class="popup-content">
            <form id="editUserForm" method="post" action="edit-user.php">
                <h2>Edit User</h2>
                <input type="hidden" id="editUserId" name="id">
                <label for="editUsername">Username:</label>
                <input type="text" id="editUsername" name="username" required>
                <label for="editName">Name:</label>
                <input type="text" id="editName" name="name" required>
                <label for="editEmail">Email:</label>
                <input type="email" id="editEmail" name="email" required>
                <label for="editRole">Role:</label>
                <input type="text" id="editRole" name="role" required>
                <label for="editPassword">Password (leave blank to keep current password):</label>
                <input type="password" id="editPassword" name="password">
                <button type="submit">Save Changes</button>
                <button type="button" class="cancelBtn">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Success Popup -->
    <div id="successPopup" class="popup">
        <div class="popup-content">
            <h2>Success</h2>
            <p id="successMessage"></p>
            <button type="button" class="closeSuccessBtn">Close</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Open Add User Popup
            document.querySelector('.add-button button').addEventListener('click', function() {
                document.getElementById('addUserPopup').style.display = 'flex';
            });

            // Close Popups
            document.querySelectorAll('.cancelBtn').forEach(button => {
                button.addEventListener('click', function() {
                    button.closest('.popup').style.display = 'none';
                });
            });

            // Success Popup Close Button
            document.getElementById('successPopup').querySelector('.closeSuccessBtn').addEventListener('click', function() {
                document.getElementById('successPopup').style.display = 'none';
                location.reload();
            });
        });

        // Show Edit User Popup
        function showEditUserPopup(userId) {
            fetch(`get-user.php?id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('editUserId').value = data.id;
                        document.getElementById('editUsername').value = data.username;
                        document.getElementById('editName').value = data.name;
                        document.getElementById('editEmail').value = data.email;
                        document.getElementById('editRole').value = data.role;
                        document.getElementById('editUserPopup').style.display = 'flex';
                    }
                });
        }

        // Delete User
        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`delete-user.php?id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessPopup('User deleted successfully.');
                        } else {
                            showSuccessPopup('Failed to delete user.');
                        }
                    });
            }
        }

        // Show Success Popup
        function showSuccessPopup(message) {
            document.getElementById('successMessage').textContent = message;
            document.getElementById('successPopup').style.display = 'flex';
        }
    </script>
</body>
</html>
