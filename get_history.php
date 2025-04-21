<?php
include 'db.php';

$entry_id = $_GET['entry_id'];

// Prepare and bind
$stmt = $conn->prepare("SELECT LocationHistory.*, Locations.LocationName
                        FROM LocationHistory
                        JOIN Locations ON LocationHistory.LocationID = Locations.LocationID
                        WHERE LocationHistory.EntryID = ?
                        ORDER BY LocationHistory.ChangeDate DESC");
$stmt->bind_param("i", $entry_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['ChangeDate'], ENT_QUOTES, 'UTF-8') . " - Location: " . htmlspecialchars($row['LocationName'], ENT_QUOTES, 'UTF-8') . "</li>";
    }
    echo "</ul>";
} else {
    echo "No history found";
}

// Close the statement
$stmt->close();
?>
