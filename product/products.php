<?php
include '../php/dbconnection.php';

$companyName = "";
$companyLogo = "";
$sql = "SELECT company_name, logo FROM company WHERE company_id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $companyName = $row['company_name'];
    $companyLogo = base64_encode($row['logo']);
}

$categories = [];
$suppliers = [];
$productsData = [];

// Fetch categories
$sql = "SELECT category_id, category_name FROM category";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Fetch suppliers
$sql = "SELECT supplier_id, supplier_name FROM supplier";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = $row;
    }
}

// Fetch products
$sql = "SELECT product_id, product_name, category_id, supplier_id, price, cost, stock_quantity, expiration_date, barcode FROM product";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productsData[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
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

        .popup-content input,
        .popup-content select {
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
            <p><a href="users.php">Users</a></p>
            <p><a href="../suppliers/suppliers.php">Suppliers</a></p>
            <p><a href="../category/category.php">Category</a></p>
            <p><a href="../product/products.php">Products</a></p>
            <p><a href="../barcode_scanner/barcode_scanner.php">Barcode Scanner</a></p>
            <p><a href="../reports/reports.php">Reports</a></p>
            <p><a href="../expired/expired.php" class="expired">Expired</a></p>
            <p><a href="../creditors/creditors.php">Creditors</a></p> 
            <p><a href="logout.php">Logout</a></p>
        </div>
        
        <div class="dashboard">
            <h3 class="dashboard-header">Products</h3>
            <div><p>Product Management</p></div>
            <div class="add-button"><button id="addProductBtn">Add Product</button></div>
            <div style="overflow-x: auto;">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Supplier</th>
                        <th>Price</th>
                        <th>Cost</th>
                        <th>Stock Quantity</th>
                        <th>Expiration Date</th>
                        <th>Barcode</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($productsData as $product) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($categories[array_search($product['category_id'], array_column($categories, 'category_id'))]['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($suppliers[array_search($product['supplier_id'], array_column($suppliers, 'supplier_id'))]['supplier_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                            <td><?php echo htmlspecialchars($product['cost']); ?></td>
                            <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                            <td><?php echo htmlspecialchars($product['expiration_date']); ?></td>
                            <td><?php echo htmlspecialchars($product['barcode']); ?></td>
                            <td class="actions">
                                <a href="#" class="edit-link" data-id="<?php echo $product['product_id']; ?>">Edit</a>
                                <a href="#" class="delete-link" data-id="<?php echo $product['product_id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Product Popup -->
    <div id="addProductPopup" class="popup">
        <div class="popup-content">
            <form id="addProductForm" method="post" action="add-product.php">
                <h2>Add Product</h2>
                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="product_name" required>
                <label for="category">Category:</label>
                <select id="category" name="category_id" required>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                    <?php } ?>
                </select>
                <label for="supplier">Supplier:</label>
                <select id="supplier" name="supplier_id" required>
                    <?php foreach ($suppliers as $supplier) { ?>
                        <option value="<?php echo $supplier['supplier_id']; ?>"><?php echo htmlspecialchars($supplier['supplier_name']); ?></option>
                    <?php } ?>
                </select>
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>
                <label for="cost">Cost:</label>
                <input type="number" id="cost" name="cost" step="0.01" required>
                <label for="stockQuantity">Stock Quantity:</label>
                <input type="number" id="stockQuantity" name="stock_quantity" required>
                <label for="expirationDate">Expiration Date:</label>
                <input type="date" id="expirationDate" name="expiration_date" required>
                <label for="barcode">Barcode:</label>
                <input type="text" id="barcode" name="barcode" required>
                <button type="submit">Add Product</button>
                <button type="button" class="cancelBtn">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Edit Product Popup -->
    <div id="editProductPopup" class="popup">
        <div class="popup-content">
            <form id="editProductForm" method="post" action="edit-product.php">
                <h2>Edit Product</h2>
                <input type="hidden" id="editProductId" name="product_id">
                <label for="editProductName">Product Name:</label>
                <input type="text" id="editProductName" name="product_name" required>
                <label for="editCategory">Category:</label>
                <select id="editCategory" name="category_id" required>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                    <?php } ?>
                </select>
                <label for="editSupplier">Supplier:</label>
                <select id="editSupplier" name="supplier_id" required>
                    <?php foreach ($suppliers as $supplier) { ?>
                        <option value="<?php echo $supplier['supplier_id']; ?>"><?php echo htmlspecialchars($supplier['supplier_name']); ?></option>
                    <?php } ?>
                </select>
                <label for="editPrice">Price:</label>
                <input type="number" id="editPrice" name="price" step="0.01" required>
                <label for="editCost">Cost:</label>
                <input type="number" id="editCost" name="cost" step="0.01" required>
                <label for="editStockQuantity">Stock Quantity:</label>
                <input type="number" id="editStockQuantity" name="stock_quantity" required>
                <label for="editExpirationDate">Expiration Date:</label>
                <input type="date" id="editExpirationDate" name="expiration_date" required>
                <label for="editBarcode">Barcode:</label>
                <input type="text" id="editBarcode" name="barcode" required>
                <button type="submit">Save Changes</button>
                <button type="button" class="cancelBtn">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Popup -->
    <div id="deleteConfirmationPopup" class="popup">
        <div class="popup-content">
            <h2>Confirm Delete</h2>
            <p id="deleteConfirmationMessage">Are you sure you want to delete this product?</p>
            <button id="confirmDeleteBtn">Yes, Delete</button>
            <button type="button" class="cancelBtn">Cancel</button>
        </div>
    </div>

    <!-- Success Popup -->
    <div id="successPopup" class="popup">
        <div class="popup-content">
            <h2>Success</h2>
            <p id="successMessage"></p>
            <button class="closeSuccessBtn">Close</button>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Show/Hide Popups
        const addProductPopup = document.getElementById('addProductPopup');
        const editProductPopup = document.getElementById('editProductPopup');
        const deleteConfirmationPopup = document.getElementById('deleteConfirmationPopup');
        const successPopup = document.getElementById('successPopup');

        document.getElementById('addProductBtn').addEventListener('click', function () {
            addProductPopup.style.display = 'flex';
        });

        document.querySelectorAll('.edit-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const productId = this.getAttribute('data-id');

                // Fetch product details and populate the form
                fetch(`get-product.php?id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('editProductId').value = data.product_id;
                        document.getElementById('editProductName').value = data.product_name;
                        document.getElementById('editCategory').value = data.category_id;
                        document.getElementById('editSupplier').value = data.supplier_id;
                        document.getElementById('editPrice').value = data.price;
                        document.getElementById('editCost').value = data.cost;
                        document.getElementById('editStockQuantity').value = data.stock_quantity;
                        document.getElementById('editExpirationDate').value = data.expiration_date;
                        document.getElementById('editBarcode').value = data.barcode;
                        editProductPopup.style.display = 'flex';
                    });
            });
        });

        document.querySelectorAll('.delete-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const productId = this.getAttribute('data-id');
                document.getElementById('deleteConfirmationMessage').textContent = `Are you sure you want to delete product ID ${productId}?`;
                document.getElementById('confirmDeleteBtn').setAttribute('data-id', productId);
                deleteConfirmationPopup.style.display = 'flex';
            });
        });

        document.querySelectorAll('.popup-content .cancelBtn').forEach(button => {
            button.addEventListener('click', function () {
                this.closest('.popup').style.display = 'none';
            });
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            fetch(`delete-product.php?id=${productId}`, { method: 'DELETE' })
                .then(response => response.text())
                .then(message => {
                    document.getElementById('successMessage').textContent = message;
                    successPopup.style.display = 'flex';
                    setTimeout(() => location.reload(), 2000);
                });
        });

        document.querySelector('.closeSuccessBtn').addEventListener('click', function () {
            this.closest('.popup').style.display = 'none';
            location.reload();
        });
    });
    </script>
</body>
</html>
