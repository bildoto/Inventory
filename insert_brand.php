<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brand_name = $_POST['brand_name'];

    $stmt = $conn->prepare("INSERT INTO Brands (BrandName) VALUES (?)");
    $stmt->bind_param("s", $brand_name);

    if ($stmt->execute()) {
        header("Location: edit_brand.php");
    } else {
        echo "Error inserting record: " . $conn->error;
    }
}
?>
