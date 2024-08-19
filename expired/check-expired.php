<?php
// check-expired.php
include '../php/dbconnection.php';

$expiredProducts = [];
$today = date('Y-m-d');

$expiredSql = "SELECT product_name FROM product WHERE expiration_date < ?";
$stmt = $conn->prepare($expiredSql);
$stmt->bind_param("s", $today);
$stmt->execute();
$expiredResult = $stmt->get_result();

if ($expiredResult->num_rows > 0) {
    while ($row = $expiredResult->fetch_assoc()) {
        $expiredProducts[] = $row['product_name'];
    }
} else {
    $expiredProducts = null;
}

$stmt->close();

return $expiredProducts;
?>
