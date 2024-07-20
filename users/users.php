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
    $companyName = $row['company_name'];
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
            <div ><h3 class="dashboard-header">Users</h3></div>
            <div><p>Manage users</p></div>
            <div class="add-button"><button >Add user</button></div>
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
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            </div>
        </div>
    </div>
</body>
</html>
