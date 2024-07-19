<?php
$userCount = 0;
$supplierCount = 0;
$productCount = 0;
$storeCount = 0;

$userResult = $conn->query("SELECT COUNT(*) as count FROM users");
if ($userResult->num_rows > 0) {
    $row = $userResult->fetch_assoc();
    $userCount = $row['count'];
}

$supplierResult = $conn->query("SELECT COUNT(*) as count FROM supplier");
if ($supplierResult->num_rows > 0) {
    $row = $supplierResult->fetch_assoc();
    $supplierCount = $row['count'];
}

$productResult = $conn->query("SELECT COUNT(*) as count FROM product");
if ($productResult->num_rows > 0) {
    $row = $productResult->fetch_assoc();
    $productCount = $row['count'];
}

$storeResult = $conn->query("SELECT COUNT(*) as count FROM store");
if ($storeResult->num_rows > 0) {
    $row = $storeResult->fetch_assoc();
    $storeCount = $row['count'];
}


?>