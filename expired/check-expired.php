<?php
include '../php/dbconnection.php';

function getExpiredProducts($conn) {
    $today = date('Y-m-d');
    $sql = "SELECT product_id, product_name, expiration_date FROM product WHERE expiration_date < ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $today);
    $stmt->execute();
    $result = $stmt->get_result();
    $expiredProducts = [];

    while ($row = $result->fetch_assoc()) {
        $expiredProducts[] = $row;
    }

    return $expiredProducts;
}
?>
