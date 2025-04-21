<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entry_id = $_POST['entry_id'];
    $new_location = $_POST['new_location'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE Entries SET LocationID = ? WHERE EntryID = ?");
    $stmt->bind_param("ii", $new_location, $entry_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Location changed successfully";
    } else {
        echo "Error updating location: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>
