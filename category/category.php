<?php
include '../php/dbconnection.php';

// Database connection code
$companyName = "";
$companyLogo = "";
$sql = "SELECT company_name, logo FROM company WHERE company_id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $companyName = $row['company_name'];
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
    justify-content: center;
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
    margin-bottom: 5px; /* Margin below the button */
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
    background-color: linear-gradient(to right ,#5f3481,#b784d7 ); 
}

#successPopup .closeSuccessBtn:hover {
    background-color:#251742; 
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
            <p><a href="../creditors/creditors.php">Creditors</a></p> <br>
            <p><a href="../logout/logout.php">Logout</a></p>
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
                                    <a href="../category/" class="delete-link" data-id="<?php echo $category['category_id']; ?>">Delete</a>
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
document.addEventListener("DOMContentLoaded", function() {
    const addCategoryButton = document.querySelector(".add-button button");
    const addCategoryPopup = document.getElementById("addCategoryPopup");
    const editCategoryPopup = document.getElementById("editCategoryPopup");
    const successPopup = document.getElementById("successPopup");

    const cancelButtons = document.querySelectorAll(".cancelBtn");
    const closeSuccessBtn = document.querySelector(".closeSuccessBtn");

    addCategoryButton.addEventListener("click", function() {
        addCategoryPopup.style.display = "flex";
    });

    cancelButtons.forEach(button => {
        button.addEventListener("click", function() {
            button.closest(".popup").style.display = "none";
        });
    });

    closeSuccessBtn.addEventListener("click", function() {
        successPopup.style.display = "none";
        location.reload(); // Reload the page
    });

    document.querySelectorAll(".edit-link").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            fetch(`get-category.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status !== "error") {
                        document.getElementById("editCategoryId").value = data.category_id;
                        document.getElementById("editName").value = data.category_name;
                        document.getElementById("editDescription").value = data.description;
                        editCategoryPopup.style.display = "flex";
                    } else {
                        alert(data.message);
                    }
                });
        });
    });

    document.querySelectorAll(".delete-link").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            if (confirm("Are you sure you want to delete this category?")) {
                fetch("delete-category.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        successPopup.style.display = "flex";
                        document.getElementById("successMessage").innerText = data.message;
                    } else {
                        alert(data.message);
                    }
                });
            }
        });
    });

    document.getElementById("addCategoryForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch("add-category.php", {
            method: "POST",
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                successPopup.style.display = "flex";
                document.getElementById("successMessage").innerText = data.message;
                addCategoryPopup.style.display = "none";
            } else {
                alert(data.message);
            }
        });
    });

    document.getElementById("editCategoryForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch("edit-category.php", {
            method: "POST",
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                successPopup.style.display = "flex";
                document.getElementById("successMessage").innerText = data.message;
                editCategoryPopup.style.display = "none";
            } else {
                alert(data.message);
            }
        });
    });
});

</script> 
    
</body>
</html>
