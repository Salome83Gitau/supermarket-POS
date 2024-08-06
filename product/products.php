<?php
include '../php/dbconnection.php';

// Fetch products with category and supplier names
$sql = "SELECT 
            p.product_id, 
            p.product_name, 
            c.category_name, 
            s.supplier_name, 
            p.price, 
            p.cost, 
            p.stock_quantity, 
            p.expiration_date, 
            p.barcode 
        FROM 
            product p
        LEFT JOIN 
            category c ON p.category_id = c.category_id
        LEFT JOIN 
            supplier s ON p.supplier_id = s.supplier_id";

$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Product Management</h1>
        <button id="addProductBtn">Add Product</button>
        <table>
            <thead>
                <tr>
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
            </thead>
            <tbody>
                <?php foreach ($products as $product) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['supplier_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['cost']); ?></td>
                        <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($product['expiration_date']); ?></td>
                        <td><?php echo htmlspecialchars($product['barcode']); ?></td>
                        <td>
                            <button class="edit-btn" data-id="<?php echo $product['product_id']; ?>">Edit</button>
                            <button class="delete-btn" data-id="<?php echo $product['product_id']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Product Popup -->
    <div id="addProductPopup" class="popup">
        <div class="popup-content">
            <form id="addProductForm" method="post" action="add-product.php">
                <h2>Add Product</h2>
                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="product_name" required>
                <label for="categoryId">Category:</label>
                <select id="categoryId" name="category_id" required>
                    <!-- Options will be populated by JavaScript -->
                </select>
                <label for="supplierId">Supplier:</label>
                <select id="supplierId" name="supplier_id" required>
                    <!-- Options will be populated by JavaScript -->
                </select>
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" required>
                <label for="cost">Cost:</label>
                <input type="number" step="0.01" id="cost" name="cost" required>
                <label for="stockQuantity">Stock Quantity:</label>
                <input type="number" id="stockQuantity" name="stock_quantity" required>
                <label for="expirationDate">Expiration Date:</label>
                <input type="date" id="expirationDate" name="expiration_date">
                <label for="barcode">Barcode:</label>
                <input type="text" id="barcode" name="barcode">
                <button type="submit">Add Product</button>
                <button type="button" class="cancel-btn">Cancel</button>
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
                <label for="editCategoryId">Category:</label>
                <select id="editCategoryId" name="category_id" required>
                    <!-- Options will be populated by JavaScript -->
                </select>
                <label for="editSupplierId">Supplier:</label>
                <select id="editSupplierId" name="supplier_id" required>
                    <!-- Options will be populated by JavaScript -->
                </select>
                <label for="editPrice">Price:</label>
                <input type="number" step="0.01" id="editPrice" name="price" required>
                <label for="editCost">Cost:</label>
                <input type="number" step="0.01" id="editCost" name="cost" required>
                <label for="editStockQuantity">Stock Quantity:</label>
                <input type="number" id="editStockQuantity" name="stock_quantity" required>
                <label for="editExpirationDate">Expiration Date:</label>
                <input type="date" id="editExpirationDate" name="expiration_date">
                <label for="editBarcode">Barcode:</label>
                <input type="text" id="editBarcode" name="barcode">
                <button type="submit">Save Changes</button>
                <button type="button" class="cancel-btn">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Success Popup -->
    <div id="successPopup" class="popup">
        <div class="popup-content">
            <h2>Success</h2>
            <p id="successMessage"></p>
            <button class="close-success-btn">Close</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addProductBtn = document.getElementById("addProductBtn");
            const addProductPopup = document.getElementById("addProductPopup");
            const editProductPopup = document.getElementById("editProductPopup");
            const successPopup = document.getElementById("successPopup");
            const successMessage = document.getElementById("successMessage");

            addProductBtn.addEventListener("click", function() {
                addProductPopup.style.display = "flex";
                loadCategoriesAndSuppliers();
            });

            document.querySelectorAll(".cancel-btn").forEach(button => {
                button.addEventListener("click", function() {
                    addProductPopup.style.display = "none";
                    editProductPopup.style.display = "none";
                });
            });

            document.querySelectorAll(".edit-btn").forEach(button => {
                button.addEventListener("click", function() {
                    const id = this.dataset.id;
                    fetch(`get-product.php?id=${id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                const product = data.product;
                                document.getElementById("editProductId").value = product.product_id;
                                document.getElementById("editProductName").value = product.product_name;
                                document.getElementById("editCategoryId").value = product.category_id;
                                document.getElementById("editSupplierId").value = product.supplier_id;
                                document.getElementById("editPrice").value = product.price;
                                document.getElementById("editCost").value = product.cost;
                                document.getElementById("editStockQuantity").value = product.stock_quantity;
                                document.getElementById("editExpirationDate").value = product.expiration_date;
                                document.getElementById("editBarcode").value = product.barcode;
                                editProductPopup.style.display = "flex";
                                loadCategoriesAndSuppliers(true);
                            } else {
                                alert(data.message);
                            }
                        });
                });
            });

            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function() {
                    if (confirm("Are you sure you want to delete this product?")) {
                        const id = this.dataset.id;
                        fetch(`delete-product.php`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ id: id })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                successMessage.textContent = data.message;
                                successPopup.style.display = "flex";
                                document.querySelector(".close-success-btn").addEventListener("click", function() {
                                    successPopup.style.display = "none";
                                    location.reload();
                                });
                            } else {
                                alert(data.message);
                            }
                        });
                    }
                });
            });

            function loadCategoriesAndSuppliers(isEdit = false) {
                fetch('get-categories.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            const categorySelect = document.getElementById(isEdit ? "editCategoryId" : "categoryId");
                            categorySelect.innerHTML = '';
                            data.categories.forEach(category => {
                                const option = document.createElement("option");
                                option.value = category.category_id;
                                option.textContent = category.category_name;
                                categorySelect.appendChild(option);
                            });
                        }
                    });

                fetch('get-suppliers.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            const supplierSelect = document.getElementById(isEdit ? "editSupplierId" : "supplierId");
                            supplierSelect.innerHTML = '';
                            data.suppliers.forEach(supplier => {
                                const option = document.createElement("option");
                                option.value = supplier.supplier_id;
                                option.textContent = supplier.supplier_name;
                                supplierSelect.appendChild(option);
                            });
                        }
                    });
            }
        });
    </script>
</body>
</html>
