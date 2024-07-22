<?php
session_start();
include '../php/dbconnection.php';
include '../functions/count.php';

$companyName = "";
$companyLogo = "";
$sql = "SELECT company_name, logo FROM company WHERE company_id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $companyName = $row ['company_name'];
    $companyLogo = base64_encode($row['logo']); 
}

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
    <script src="../js/addProduct.js"></script>
    <style>
        /* Success Message Popup Styles */
        #successPopup .popup-content {
            background-color: #dff0d8; /* Light green background */
            color: #3c763d; /* Dark green text */
            border: 1px solid #d6e9c6; /* Green border */
        }

        #successPopup .popup-content h2 {
            margin-top: 0;
        }

        #successPopup .popup-content p {
            margin: 10px 0;
        }

        #successPopup .popup-content button.closeSuccessBtn {
            background-color: #3c763d; /* Dark green */
            color: white;
        }

        #successPopup .popup-content button.closeSuccessBtn:hover {
            background-color: #2d6a4f; /* Darker green */
        }

        /* Popup styles */
        .popup {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            z-index: 1000; /* Ensure it's on top */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
        }

        .popup-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .popup-content h2 {
            margin-top: 0;
        }

        .popup-content input[type=text],
        .popup-content input[type=email],
        .popup-content input[type=password] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .popup-content button {
            background-color: #04AA6D;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-right: 10px;
        }

        .popup-content button.cancelBtn {
            background-color: red;
        }

        .popup-content button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="Dashboardwrapper">
        <div class="sidebar">
            <div class="company-info">
                <?php if ($companyLogo): ?>
                    <img src="data:image/png;base64,<?php echo $companyLogo; ?>" alt="Company Logo" height="50">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($companyName); ?></h2>
            </div>
            <p><a href="../php/dashboard.php">Dashboard</a></p>
            <p><a href="stores.php">Stores</a></p>
            <p><a href="../users/users.php">Users</a></p>
            <p><a href="suppliers.php">Suppliers</a></p>
            <p><a href="category.php">Category</a></p>
            <p><a href="../product/products.php">Products</a></p>
            <p><a href="barcode_scanner.php">Barcode Scanner</a></p>
            <p><a href="reports.php">Reports</a></p>
            <p><a href="expired.php" class="expired">Expired</a></p>
            <p><a href="creditors.php">Creditors</a></p> <br>
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
                    <?php foreach ($userData as $user) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <a href="#" class="edit-link" data-id="<?php echo $user['id']; ?>">Edit</a>
                                <a href="delete-user.php?id=<?php echo $user['id']; ?>" class="delete-link">Delete</a>
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
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <label for="role">Role:</label>
            <input type="text" id="role" name="role" placeholder="Role" required>
            <button type="submit" id="confirmAddUser">Add User</button> <br>
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
            <button type="submit" id="confirmEditUser">Save Changes</button> <br>
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
    document.addEventListener('DOMContentLoaded', () => {
        const addUserForm = document.getElementById('addUserForm');
        const editUserForm = document.getElementById('editUserForm');
        const addUserPopup = document.getElementById('addUserPopup');
        const editUserPopup = document.getElementById('editUserPopup');
        const successPopup = document.getElementById('successPopup');
        const addButton = document.querySelector('.add-button button');
        const cancelButtons = document.querySelectorAll('.cancelBtn');
        const closeSuccessButton = document.querySelector('#successPopup .closeSuccessBtn');

        function openPopup(popup) {
            popup.style.display = 'flex';
        }

        function closePopup(popup) {
            popup.style.display = 'none';
        }

        function openSuccessPopup(message) {
            document.getElementById('successMessage').textContent = message;
            openPopup(successPopup);
        }

        function closeSuccessPopup() {
            closePopup(successPopup);
        }

        function handleFormSubmission(event, form, action) {
            event.preventDefault(); // Prevent default form submission
            
            if (confirm("Are you sure you want to proceed with this action?")) {
                fetch(action, {
                    method: 'POST',
                    body: new FormData(form)
                })
                .then(response => response.text())
                .then(result => {
                    if (result.includes("success")) {
                        openSuccessPopup("Action completed successfully");
                        closePopup(addUserPopup); // Close the add user popup
                        closePopup(editUserPopup); // Close the edit user popup
                        window.location.reload(); // Reload the page to reflect changes
                    } else {
                        alert("Error: " + result);
                    }
                })
                .catch(error => alert("Error: " + error));
            }
        }

        addButton.addEventListener('click', () => openPopup(addUserPopup));

        cancelButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const popup = event.target.closest('.popup');
                closePopup(popup);
            });
        });

        closeSuccessButton.addEventListener('click', closeSuccessPopup);

        addUserForm.addEventListener('submit', (event) => handleFormSubmission(event, addUserForm, 'add-user.php'));

        editUserForm.addEventListener('submit', (event) => handleFormSubmission(event, editUserForm, 'edit-user.php'));

        document.querySelectorAll('.edit-link').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                const userId = this.getAttribute('data-id');
                
                fetch(`get-user.php?id=${userId}`)
                .then(response => response.json())
                .then(user => {
                    document.getElementById('editUserId').value = user.id;
                    document.getElementById('editUsername').value = user.username;
                    document.getElementById('editName').value = user.name;
                    document.getElementById('editEmail').value = user.email;
                    document.getElementById('editRole').value = user.role;
                    openPopup(editUserPopup);
                })
                .catch(error => alert("Error: " + error));
            });
        });
    });
</script>

</body>
</html>
