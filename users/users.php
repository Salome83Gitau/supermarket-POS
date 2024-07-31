<?php
include '../php/dbconnection.php';

// Fetch company details
$categoryName = "";
$companyLogo = "";
$sql = "SELECT company_name, logo FROM company WHERE company_id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $categoryName = $row['company_name'];
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
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            top: 0;
            left: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            background: rgba(0, 0, 0, 0.5); /* Black background with opacity */
            z-index: 1000; /* On top */
            justify-content: center; /* Center vertically */
            align-items: center; /* Center horizontally */
        }

        .popup-content {
            background: #fff; /* White background */
            padding: 20px; /* Padding inside the popup */
            border-radius: 5px; /* Rounded corners */
            width: 300px; /* Fixed width */
            box-shadow: 0 0 10px rgba(0,0,0,0.2); /* Shadow */
            position: relative; /* Position relative for inner positioning */
        }

        .popup-content h2 {
            margin-top: 0; /* No margin at the top */
            margin-bottom: 10px; /* Margin at the bottom */
        }

        .popup-content form {
            display: flex;
            flex-direction: column; /* Arrange children in a column */
        }

        .popup-content label {
            margin-bottom: 5px; /* Margin below the label */
        }

        .popup-content input {
            margin-bottom: 10px; /* Margin below the input */
            padding: 8px; /* Padding inside the input */
            border: 1px solid #ddd; /* Border around the input */
            border-radius: 3px; /* Rounded corners */
        }

        .popup-content button {
            padding: 10px; /* Padding inside the button */
            border: none; /* No border */
            border-radius: 3px; /* Rounded corners */
            background-color: #007bff; /* Blue background */
            color: #fff; /* White text */
            cursor: pointer; /* Pointer cursor */
        }

        /* Success Popup Styles */
        #successPopup {
            display: none; /* Hidden by default */
        }

        #successPopup h2 {
            margin-top: 0; /* No margin at the top */
        }

        #successPopup #successMessage {
            margin-bottom: 15px;
        }

        #successPopup .closeSuccessBtn {
            background-color: #28a745; /* Green background */
        }

        #successPopup .closeSuccessBtn:hover {
            background-color: #218838; /* Darker green on hover */
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
            <h2><?php echo htmlspecialchars($categoryName); ?></h2>
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
        <p><a href="../creditors/creditors.php">Creditors</a></p>
        <br>
        <p><a href="logout.php">Logout</a></p>
    </div>
    <div class="dashboard">
        <div>
            <h3 class="dashboard-header">Users</h3>
        </div>
        <div>
            <p>User Management</p>
        </div>
        <div class="add-button">
            <button>Add User</button>
        </div>
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
                    <?php foreach ($userData as $user) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="actions">
                                <a href="#" class="edit-link" data-id="<?php echo $user['id']; ?>">Edit</a>
                                <a href="../users/delete-user.php" class="delete-link" data-id="<?php echo $user['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
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
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <label for="role">Role:</label>
            <input type="text" id="role" name="role" placeholder="Role" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit" id="confirmAddUser">Add User</button>
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
            <input type="text" id="editUsername" name="username" placeholder="Username" required>
            <label for="editName">Name:</label>
            <input type="text" id="editName" name="name" placeholder="Name" required>
            <label for="editEmail">Email:</label>
            <input type="email" id="editEmail" name="email" placeholder="Email" required>
            <label for="editRole">Role:</label>
            <input type="text" id="editRole" name="role" placeholder="Role" required>
            <label for="editPassword">Password:</label>
            <input type="password" id="editPassword" name="password" placeholder="Password">
            <button type="submit" id="confirmEditUser">Save Changes</button>
            <button type="button" class="cancelBtn">Cancel</button>
        </form>
    </div>
</div>

<!-- Success Message Popup -->
<div id="successPopup" class="popup">
    <div class="popup-content">
        <h2>Success</h2>
        <p id="successMessage">Action completed successfully!</p>
        <button type="button" class="closeSuccessBtn">Close</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addUserBtn = document.querySelector('.add-button button');
    const addUserPopup = document.getElementById('addUserPopup');
    const editUserPopup = document.getElementById('editUserPopup');
    const successPopup = document.getElementById('successPopup');
    const addUserForm = document.getElementById('addUserForm');
    const editUserForm = document.getElementById('editUserForm');

    addUserBtn.addEventListener('click', () => {
        addUserPopup.style.display = 'flex';
    });

    document.querySelectorAll('.edit-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const userId = link.getAttribute('data-id');
            fetch(`../users/get-user.php?id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editUserId').value = data.id;
                    document.getElementById('editUsername').value = data.username;
                    document.getElementById('editName').value = data.name;
                    document.getElementById('editEmail').value = data.email;
                    document.getElementById('editRole').value = data.role;
                    editUserPopup.style.display = 'flex';
                });
        });
    });

    document.querySelectorAll('.cancelBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            addUserPopup.style.display = 'none';
            editUserPopup.style.display = 'none';
        });
    });

    document.querySelector('.closeSuccessBtn').addEventListener('click', () => {
        successPopup.style.display = 'none';
        location.reload();
    });

    addUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(addUserForm);
        fetch(addUserForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('successMessage').textContent = 'User added successfully!';
                successPopup.style.display = 'flex';
            }
        });
    });

    editUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(editUserForm);
        fetch(editUserForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('successMessage').textContent = 'User updated successfully!';
                successPopup.style.display = 'flex';
            }
        });
    });
});
</script>
</body>
</html>
