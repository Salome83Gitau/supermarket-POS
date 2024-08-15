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
                
            <p><a href="../cashier/cashier-dashboard.php">Dashboard</a></p>
            <p><a href="#">Category</a></p>
            <p><a href="../cashier/products.php">Products</a></p>
            <p><a href="barcode_scanner.php">Barcode Scanner</a></p>
            <p><a href="../reports/reports.php">Reports</a></p>
            <p><a href="POS" >POS</a></p>
            <p><a href="credit">Credit sales</a></p> <br>
            <p><a href="../logout/logout.php" style="bottom: 10px;">Logout</a></p>
        </div>
        <div class="dashboard">
            <div><h3 class="dashboard-header">Categories</h3></div>
            <div><p>Category Management</p></div>
            <div style="overflow-x: auto;">
                <div class="table">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                        </tr>
                        <?php foreach ($categoryData as $category) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($category['category_id']); ?></td>
                                <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                                <td><?php echo htmlspecialchars($category['description']); ?></td>
                            </tr>
                            <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
