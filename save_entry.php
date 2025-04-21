<?php
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brand_id = $_POST['brand_id'];
    $model = $_POST['model'];
    $serial_number = $_POST['serial_number'];
    $eur = $_POST['eur'] !== '' ? $_POST['eur'] : null; // Handle empty decimal value
    $location_id = $_POST['location_id'];
    $device_id = $_POST['device_id'];
    $comments = $_POST['comments'];
    $acquisition_date = $_POST['acquisition_date'] !== '' ? $_POST['acquisition_date'] : null; // Handle empty date value

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO Entries (BrandID, Model, SerialNumber, Eur, LocationID, DeviceID, Comments, AcquisitionDate)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issiiiss", $brand_id, $model, $serial_number, $eur, $location_id, $device_id, $comments, $acquisition_date);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>
