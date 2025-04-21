<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $device_id = $_POST['device_id'];
    $device_name = $_POST['device_name'];

    $stmt = $conn->prepare("UPDATE Devices SET DeviceName = ? WHERE DeviceID = ?");
    $stmt->bind_param("si", $device_name, $device_id);

    if ($stmt->execute()) {
        header("Location: edit_device.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
