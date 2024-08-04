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
$sql = "SELECT category_id, category_name FROM category";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categoryData[] = $row;
    }
}

$supplierData = [];
$sql = "SELECT supplier_id, supplier_name FROM supplier";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $supplierData[] = $row;
    }
}

$productData = [];
$sql = "SELECT * FROM product";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productData[] = $row;
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
            <p><a href="logout.php">Logout</a></p>
        </div>
        <div class="dashboard">
            <h3 class="dashboard-header">Products</h3>
            <div><p>Product Management</p></div>
            <div class="add-button"><button>Add Product</button></div>
            <div style="overflow-x: auto;">
                <div class="table">
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
                        <?php foreach ($productData as $product) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($product['category_id']); ?></td>
                                <td><?php echo htmlspecialchars($product['supplier_id']); ?></td>
                                <td><?php echo htmlspecialchars($product['price']); ?></td>
                                <td><?php echo htmlspecialchars($product['cost']); ?></td>
                                <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                                <td><?php echo htmlspecialchars($product['expiration_date']); ?></td>
                                <td><?php echo htmlspecialchars($product['barcode']); ?></td>
                                <td class="actions">
                                    <a href="#" class="edit-link" data-id="<?php echo $product['product_id']; ?>">Edit</a>
                                    <a href="../product/delete-product.php" class="delete-link" data-id="<?php echo $product['product_id']; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Popup -->
    <div id="addProductPopup" class="popup">
        <div class="popup-content">
            <form id="addProductForm" method="post" action="add-product.php">
                <h2>Add Product</h2>
                <label for="productName">Name:</label>
                <input type="text" id="productName" name="product_name" placeholder="Product Name" required>
                
                <label for="category">Category:</label>
                <select id="category" name="category_id" required>
                    <?php foreach ($categoryData as $category) { ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                    <?php } ?>
                </select>

                <label for="supplier">Supplier:</label>
                <select id="supplier" name="supplier_id" required>
                    <?php foreach ($supplierData as $supplier) { ?>
                        <option value="<?php echo $supplier['supplier_id']; ?>"><?php echo htmlspecialchars($supplier['supplier_name']); ?></option>
                    <?php } ?>
                </select>

                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" placeholder="Price" required>

                <label for="cost">Cost:</label>
                <input type="number" step="0.01" id="cost" name="cost" placeholder="Cost" required>

                <label for="stock_quantity">Stock Quantity:</label>
                <input type="number" id="stock_quantity" name="stock_quantity" placeholder="Stock Quantity" required>

                <label for="expiration_date">Expiration Date:</label>
                <input type="date" id="expiration_date" name="expiration_date" placeholder="Expiration Date" required>

                <label for="barcode">Barcode:</label>
                <input type="text" id="barcode" name="barcode" placeholder="Barcode" required>

                <button type="submit" id="confirmAddProduct">Add Product</button>
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
                
                <label for="editProductName">Name:</label>
                <input type="text" id="editProductName" name="product_name" placeholder="Product Name" required>

                <label for="editCategory">Category:</label>
                <select id="editCategory" name="category_id" required>
                    <?php foreach ($categoryData as $category) { ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                    <?php } ?>
                </select>

                <label for="editSupplier">Supplier:</label>
                <select id="editSupplier" name="supplier_id" required>
                    <?php foreach ($supplierData as $supplier) { ?>
                        <option value="<?php echo $supplier['supplier_id']; ?>"><?php echo htmlspecialchars($supplier['supplier_name']); ?></option>
                    <?php } ?>
                </select>

                <label for="editPrice">Price:</label>
                <input type="number" step="0.01" id="editPrice" name="price" placeholder="Price" required>

                <label for="editCost">Cost:</label>
                <input type="number" step="0.01" id="editCost" name="cost" placeholder="Cost" required>

                <label for="editStockQuantity">Stock Quantity:</label>
                <input type="number" id="editStockQuantity" name="stock_quantity" placeholder="Stock Quantity" required>

                <label for="editExpirationDate">Expiration Date:</label>
                <input type="date" id="editExpirationDate" name="expiration_date" placeholder="Expiration Date" required>

                <label for="editBarcode">Barcode:</label>
                <input type="text" id="editBarcode" name="barcode" placeholder="Barcode" required>

                <button type="submit" id="confirmEditProduct">Save Changes</button>
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
                        }
                    })
                    .catch(error => console.error("Error fetching product data:", error));
            });
        });

        document.querySelectorAll(".delete-link").forEach(link => {
            link.addEventListener("click", function(e) {
                e.preventDefault();
                if (confirm("Are you sure you want to delete this product?")) {
                    const id = this.dataset.id;
                    fetch(`delete-product.php?id=${id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                successPopup.style.display = "flex";
                                document.getElementById("successMessage").textContent = "Product deleted successfully!";
                            } else {
                                alert("Error deleting product: " + data.message);
                            }
                        })
                        .catch(error => console.error("Error deleting product:", error));
                }
            });
        });
    });
    </script>
</body>
</html>
