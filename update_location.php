<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location_id = $_POST['location_id'];
    $location_name = $_POST['location_name'];

    $stmt = $conn->prepare("UPDATE Locations SET LocationName = ? WHERE LocationID = ?");
    $stmt->bind_param("si", $location_name, $location_id);

    if ($stmt->execute()) {
        header("Location: edit_location.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
