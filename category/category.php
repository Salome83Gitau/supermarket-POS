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
            <p><a href="../creditors/creditors.php">Creditors</a></p> <br>
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

    
</body>
</html>
