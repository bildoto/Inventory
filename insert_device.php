<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $device_name = $_POST['device_name'];

    $stmt = $conn->prepare("INSERT INTO Devices (DeviceName) VALUES (?)");
    $stmt->bind_param("s", $device_name);

    if ($stmt->execute()) {
        header("Location: edit_device.php");
    } else {
        echo "Error inserting record: " . $conn->error;
    }
}
?>
