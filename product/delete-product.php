<?php
include '../php/dbconnection.php';

$id = $_POST['id'];

if (isset($id)) {
    $sql = "DELETE FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Reorder the product_ids
        $sql_reorder = "SET @row_number = 0;
                        UPDATE product SET product_id = (@row_number := @row_number + 1) ORDER BY product_id;";
        $conn->multi_query($sql_reorder);

        echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete product.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product ID.']);
}

$conn->close();
?>
