<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brand_id = $_POST['brand_id'];
    $brand_name = $_POST['brand_name'];

    $stmt = $conn->prepare("UPDATE Brands SET BrandName = ? WHERE BrandID = ?");
    $stmt->bind_param("si", $brand_name, $brand_id);

    if ($stmt->execute()) {
        header("Location: edit_brand.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
