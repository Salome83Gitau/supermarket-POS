<?php
include '../php/dbconnection.php';

// Database connection code
$categoryName = "";
$companyLogo = "";
$sql = "SELECT company_name, logo FROM company WHERE company_id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $categoryName = $row['company_name'];
    $companyLogo = base64_encode($row['logo']);
}

$categoryData = [];
$sql = "SELECT category_id, category_name, description FROM category";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categoryData[] = $row;
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
    <script src="../js/addCategory.js"></script>
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
                <h2><?php echo htmlspecialchars($categoryName); ?></h2>
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
            <div><h3 class="dashboard-header">Categories</h3></div>
            <div><p>Category Management</p></div>
            <div class="add-button"><button>Add Category</button></div>
            <div style="overflow-x: auto;">
                <div class="table">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                        <?php foreach ($categoryData as $category) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($category['category_id']); ?></td>
                                <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                                <td><?php echo htmlspecialchars($category['description']); ?></td>
                                <td class="actions">
                                    <a href="#" class="edit-link" data-id="<?php echo $category['category_id']; ?>">Edit</a>
                                    <a href="../category/delete-category.php" class="delete-link" data-id="<?php echo $category['category_id']; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Popup -->
    <div id="addCategoryPopup" class="popup">
        <div class="popup-content">
            <form id="addCategoryForm" method="post" action="add-category.php">
                <h2>Add Category</h2>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Category Name" required>
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" placeholder="Description" required>
                <button type="submit" id="confirmAddCategory">Add Category</button> <br>
                <button type="button" class="cancelBtn">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Edit Category Popup -->
    <div id="editCategoryPopup" class="popup">
        <div class="popup-content">
            <form id="editCategoryForm" method="post" action="edit-category.php">
                <h2>Edit Category</h2>
                <input type="hidden" id="editCategoryId" name="id">
                <label for="editName">Name:</label>
                <input type="text" id="editName" name="name" placeholder="Category Name" required>
                <label for="editDescription">Description:</label>
                <input type="text" id="editDescription" name="description" placeholder="Description" required>
                <button type="submit" id="confirmEditCategory">Save Changes</button> <br>
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
            const addCategoryForm = document.getElementById('addCategoryForm');
            const editCategoryForm = document.getElementById('editCategoryForm');
            const addCategoryPopup = document.getElementById('addCategoryPopup');
            const editCategoryPopup = document.getElementById('editCategoryPopup');
            const successPopup = document.getElementById('successPopup');
            const closeSuccessButton = document.querySelector('#successPopup .closeSuccessBtn');
            const addButton = document.querySelector('.add-button button');
            const cancelButtons = document.querySelectorAll('.cancelBtn');

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
                            closePopup(addCategoryPopup); // Close the add category popup
                            closePopup(editCategoryPopup); // Close the edit category popup
                            window.location.reload(); // Reload the page to reflect changes
                        } else {
                            alert("Error: " + result);
                        }
                    })
                    .catch(error => alert("Error: " + error));
                }
            }

            addButton.addEventListener('click', () => openPopup(addCategoryPopup));

            cancelButtons.forEach(button => {
                button.addEventListener('click', (event) => {
                    const popup = event.target.closest('.popup');
                    closePopup(popup);
                });
            });

            closeSuccessButton.addEventListener('click', closeSuccessPopup);

            addCategoryForm.addEventListener('submit', (event) => handleFormSubmission(event, addCategoryForm, 'add-category.php'));

            editCategoryForm.addEventListener('submit', (event) => handleFormSubmission(event, editCategoryForm, 'edit-category.php'));

            document.querySelectorAll('.edit-link').forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default link behavior
                    const categoryId = this.getAttribute('data-id');
                    
                    fetch(`get-category.php?id=${categoryId}`)
                    .then(response => response.json())
                    .then(category => {
                        document.getElementById('editCategoryId').value = category.category_id;
                        document.getElementById('editName').value = category.category_name;
                        document.getElementById('editDescription').value = category.description;
                        openPopup(editCategoryPopup);
                    })
                    .catch(error => alert("Error: " + error));
                });
            });

            document.querySelectorAll('.delete-link').forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default link behavior
                    const categoryId = this.getAttribute('data-id');
                    
                    if (confirm("Are you sure you want to delete this category?")) {
                        fetch(`delete-category.php?id=${categoryId}`, {
                            method: 'POST'
                        })
                        .then(response => response.text())
                        .then(result => {
                            if (result.includes("success")) {
                                openSuccessPopup("Category deleted successfully");
                                window.location.reload(); // Reload the page to reflect changes
                            } else {
                                alert("Error: " + result);
                            }
                        })
                        .catch(error => alert("Error: " + error));
                    }
                });
            });
        });
    </script>

</body>
</html>
