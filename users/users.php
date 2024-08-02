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
            justify-content: center;
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
            background-color: linear-gradient(to right ,#5f3481,#b784d7 );
        }

        .popup-content button.closeSuccessBtn:hover {
            background-color:#251742; 
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
            <p><a href="../stores/stores.php">Stores</a></p>
            <p><a href="../users/users.php">Users</a></p>
            <p><a href="../suppliers/suppliers.php">Suppliers</a></p>
            <p><a href="#">Category</a></p>
            <p><a href="../product/products.php">Products</a></p>
            <p><a href="barcode_scanner.php">Barcode Scanner</a></p>
            <p><a href="../reports/reports.php">Reports</a></p>
            <p><a href="../expired/expired.php" class="expired">Expired</a></p>
            <p><a href="../creditors/creditors.php">Creditors</a></p><br>
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
                        <tbody id="userTableBody"></tbody>
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
            fetchUsers();

            document.querySelector('.add-button button').addEventListener('click', function() {
                document.getElementById('addUserPopup').style.display = 'flex';
            });

            document.querySelectorAll('.cancelBtn').forEach(button => {
                button.addEventListener('click', function() {
                    button.closest('.popup').style.display = 'none';
                });
            });

            document.getElementById('addUserForm').addEventListener('submit', function(event) {
                event.preventDefault();
                handleFormSubmit(event.target, 'add-user.php');
            });

            document.getElementById('editUserForm').addEventListener('submit', function(event) {
                event.preventDefault();
                handleFormSubmit(event.target, 'edit-user.php');
            });

            document.querySelector('.closeSuccessBtn').addEventListener('click', function() {
                document.getElementById('successPopup').style.display = 'none';
                location.reload(); // Reload the page to reflect changes
            });
        });

        function fetchUsers() {
            fetch('get-users.php')
                .then(response => response.json())
                .then(data => {
                    const userTableBody = document.getElementById('userTableBody');
                    userTableBody.innerHTML = ''; // Clear existing rows

                    data.forEach(user => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${user.id}</td>
                            <td>${user.username}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${user.role}</td>
                            <td>
                                <button onclick="editUser(${user.id})">Edit</button>
                                <button onclick="deleteUser(${user.id})">Delete</button>
                            </td>
                        `;
                        userTableBody.appendChild(row);
                    });
                });
        }

        function editUser(userId) {
            fetch(`get-user.php?id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const user = data.user;
                        document.getElementById('editUserId').value = user.id;
                        document.getElementById('editUsername').value = user.username;
                        document.getElementById('editName').value = user.name;
                        document.getElementById('editEmail').value = user.email;
                        document.getElementById('editRole').value = user.role;
                        document.getElementById('editUserPopup').style.display = 'flex';
                    } else {
                        alert(data.message);
                    }
                });
        }

        function deleteUser(userId) {
            // Implement delete user functionality here
        }

        function handleFormSubmit(form, actionUrl) {
            const formData = new FormData(form);
            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('successMessage').textContent = data.message;
                    document.getElementById('successPopup').style.display = 'flex';
                    form.closest('.popup').style.display = 'none';
                } else {
                    alert(data.message);
                }
            });
        }
    </script>
</body>
</html>
