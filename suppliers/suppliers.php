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

$suppliersData = [];
$sql = "SELECT supplier_id, supplier_name, email, phone, product_name FROM supplier";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suppliersData[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Management</title>
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
            background: linear-gradient(to right, #5f3481, #b784d7);
        }

        .popup-content button.closeSuccessBtn:hover {
            background-color: #251742;
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
            <p><a href="suppliers.php">Suppliers</a></p>
            <p><a href="../category/category.php">Category</a></p>
            <p><a href="../product/products.php">Products</a></p>
            <p><a href="../barcode_scanner/barcode_scanner.php">Barcode Scanner</a></p>
            <p><a href="../reports/reports.php">Reports</a></p>
            <p><a href="../expired/expired.php" class="expired">Expired</a></p>
            <p><a href="../creditors/creditors.php">Creditors</a></p>
            <p><a href="logout.php">Logout</a></p>
        </div>
        <div class="dashboard">
            <h3 class="dashboard-header">Suppliers</h3>
            <div class="add-button"><button>Add Supplier</button></div>
            <div style="overflow-x: auto;">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Supplier Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Product Name</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($suppliersData as $supplier) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($supplier['supplier_id']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['supplier_name']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['email']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['phone']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['product_name']); ?></td>
                            <td class="actions">
                                <a href="#" class="edit-link" data-id="<?php echo $supplier['supplier_id']; ?>">Edit</a>
                                <a href="#" class="delete-link" data-id="<?php echo $supplier['supplier_id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Supplier Popup -->
    <div id="addSupplierPopup" class="popup">
        <div class="popup-content">
            <form id="addSupplierForm" method="post" action="add-supplier.php">
                <h2>Add Supplier</h2>
                <label for="supplier_name">Supplier Name:</label>
                <input type="text" id="supplier_name" name="supplier_name" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>
                <button type="submit">Add Supplier</button>
                <button type="button" class="cancelBtn">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Edit Supplier Popup -->
    <div id="editSupplierPopup" class="popup">
        <div class="popup-content">
            <form id="editSupplierForm" method="post" action="edit-supplier.php">
                <h2>Edit Supplier</h2>
                <input type="hidden" id="editSupplierId" name="supplier_id">
                <label for="editSupplierName">Supplier Name:</label>
                <input type="text" id="editSupplierName" name="supplier_name" required>
                <label for="editEmail">Email:</label>
                <input type="email" id="editEmail" name="email" required>
                <label for="editPhone">Phone:</label>
                <input type="text" id="editPhone" name="phone" required>
                <label for="editProductName">Product Name:</label>
                <input type="text" id="editProductName" name="product_name" required>
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
            const addSupplierButton = document.querySelector(".add-button button");
            const addSupplierPopup = document.getElementById("addSupplierPopup");
            const editSupplierPopup = document.getElementById("editSupplierPopup");
            const successPopup = document.getElementById("successPopup");

            const cancelButtons = document.querySelectorAll(".cancelBtn");
            const closeSuccessBtn = document.querySelector(".closeSuccessBtn");

            addSupplierButton.addEventListener("click", function() {
                addSupplierPopup.style.display = "flex";
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
                    fetch(`get-supplier.php?id=${id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status !== "error") {
                                document.getElementById("editSupplierId").value = data.supplier_id;
                                document.getElementById("editSupplierName").value = data.supplier_name;
                                document.getElementById("editEmail").value = data.email;
                                document.getElementById("editPhone").value = data.phone;
                                document.getElementById("editProductName").value = data.product_name;
                                editSupplierPopup.style.display = "flex";
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
                    if (confirm("Are you sure you want to delete this supplier?")) {
                        fetch("delete-supplier.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `supplier_id=${id}`
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

            document.getElementById("addSupplierForm").addEventListener("submit", function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch("add-supplier.php", {
                    method: "POST",
                    body: new URLSearchParams(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        successPopup.style.display = "flex";
                        document.getElementById("successMessage").innerText = data.message;
                        addSupplierPopup.style.display = "none";
                    } else {
                        alert(data.message);
                    }
                });
            });

            document.getElementById("editSupplierForm").addEventListener("submit", function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch("edit-supplier.php", {
                    method: "POST",
                    body: new URLSearchParams(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        successPopup.style.display = "flex";
                        document.getElementById("successMessage").innerText = data.message;
                        editSupplierPopup.style.display = "none";
                    } else {
                        alert(data.message);
                    }
                });
            });

            window.addEventListener("click", function(event) {
                if (event.target === addSupplierPopup) {
                    addSupplierPopup.style.display = "none";
                } else if (event.target === editSupplierPopup) {
                    editSupplierPopup.style.display = "none";
                } else if (event.target === successPopup) {
                    successPopup.style.display = "none";
                }
            });
        });
    </script>
</body>
</html>
