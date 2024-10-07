<?php
include '../php/dbconnection.php'; 

// Fetch company information
$companyName = "";
$companyLogo = "";
$sql = "SELECT company_name, logo FROM company WHERE company_id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $companyName = $row['company_name'];
    $companyLogo = base64_encode($row['logo']);
}

// Fetch product information
$productsData = [];
$sql = "
    SELECT p.product_id, p.product_name, p.price, p.cost, p.stock_quantity, p.expiration_date, p.barcode,
           c.category_name, s.supplier_name
    FROM product p
    JOIN category c ON p.category_id = c.category_id
    JOIN supplier s ON p.supplier_id = s.supplier_id
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productsData[] = $row;
    }
}

// Fetch categories
$categories = [];
$sql = "SELECT category_id, category_name FROM category";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Fetch suppliers
$suppliers = [];
$sql = "SELECT supplier_id, supplier_name FROM supplier";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = $row;
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
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 500px;
        }
        .popup-content button {
            margin-top: 10px;
        }
        .actions a {
            margin-right: 10px;
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
            <p><a href="products.php">Products</a></p>
            <p><a href="../barcode_scanner/barcode_scanner.php">Barcode Scanner</a></p>
            <p><a href="../reports/reports.php">Reports</a></p>
            <p><a href="../expired/expired.php" class="expired">Expired</a></p>
            <p><a href="../creditors/creditors.php">Creditors</a></p> 
            <p><a href="../logout/logout.php">Logout</a></p>
        </div>
        <div class="dashboard">
            <h3 class="dashboard-header">Products</h3>
            <div><p>Product Management</p></div>
            <div class="add-button"><button id="openAddProductPopup">Add Product</button></div>
            <div style="overflow-x: auto;">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
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
                            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['supplier_name']); ?></td>
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
    const addProductButton = document.querySelector(".add-button button");
    const addProductPopup = document.getElementById("addProductPopup");
    const editProductPopup = document.getElementById("editProductPopup");
    const successPopup = document.getElementById("successPopup");

    const cancelButtons = document.querySelectorAll(".cancelBtn");
    const closeSuccessBtn = document.querySelector(".closeSuccessBtn");

    addProductButton.addEventListener("click", function() {
        addProductPopup.style.display = "flex";
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
            fetch(`get-product.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status !== "error") {
                        document.getElementById("editProductId").value = data.product_id;
                        document.getElementById("editProductName").value = data.product_name;
                        document.getElementById("editCategory").value = data.category_id;
                        document.getElementById("editSupplier").value = data.supplier_id;
                        document.getElementById("editPrice").value = data.price;
                        document.getElementById("editCost").value = data.cost;
                        document.getElementById("editStockQuantity").value = data.stock_quantity;
                        document.getElementById("editExpirationDate").value = data.expiration_date;
                        document.getElementById("editBarcode").value = data.barcode;
                        editProductPopup.style.display = "flex";
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
            if (confirm("Are you sure you want to delete this product?")) {
                fetch("delete-product.php", {
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

    document.getElementById("addProductForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch("add-product.php", {
            method: "POST",
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                successPopup.style.display = "flex";
                document.getElementById("successMessage").innerText = data.message;
                addProductPopup.style.display = "none";
            } else {
                alert(data.message);
            }
        });
    });

    document.getElementById("editProductForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch("edit-product.php", {
            method: "POST",
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                successPopup.style.display = "flex";
                document.getElementById("successMessage").innerText = data.message;
                editProductPopup.style.display = "none";
            } else {
                alert(data.message);
            }
        });
    });

    window.addEventListener("click", function(event) {
        if (event.target === addProductPopup) {
            addProductPopup.style.display = "none";
        } else if (event.target === editProductPopup) {
            editProductPopup.style.display = "none";
        } else if (event.target === successPopup) {
            successPopup.style.display = "none";
        }
    });
});

    </script>
</body>
</html>
