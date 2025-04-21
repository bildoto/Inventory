<?php
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entry_id = $_POST['entry_id'];
    $brand_id = $_POST['brand_id'];
    $model = $_POST['model'];
    $serial_number = $_POST['serial_number'];
    $eur = $_POST['eur'] !== '' ? $_POST['eur'] : null; // Handle empty decimal value
    $location_id = $_POST['location_id'];
    $device_id = $_POST['device_id'];
    $comments = $_POST['comments'];
    $acquisition_date = $_POST['acquisition_date'] !== '' ? $_POST['acquisition_date'] : null; // Handle empty date value

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE Entries SET
                            BrandID = ?, Model = ?, SerialNumber = ?, Eur = ?, LocationID = ?, DeviceID = ?, Comments = ?, AcquisitionDate = ?
                            WHERE EntryID = ?");
    $stmt->bind_param("issiiissi", $brand_id, $model, $serial_number, $eur, $location_id, $device_id, $comments, $acquisition_date, $entry_id);

    // Execute the statement
    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Record updated successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error updating record: ' . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Return JSON response
    echo json_encode($response);
    exit;
}

$entry_id = isset($_GET['id']) ? $_GET['id'] : null; // Check if 'id' key exists
if ($entry_id) {
    $stmt = $conn->prepare("SELECT * FROM Entries WHERE EntryID = ?");
    $stmt->bind_param("i", $entry_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Entry | <?php include 'title.inc';?>
</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'edit_entry.php',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        $('#modalMessage').text(response.message);
                        $('#responseModal').modal('show');
                        if (response.status === 'success') {
                            $('#modalOkButton').on('click', function() {
                                window.location.href = 'admin.php';
                            });
                        }
                    }
                });
            });
        });
    </script>
</head>
<body>
<div class="container">
    <h1 class="mt-5"><?php include 'title.inc';?><br />Edit Entry</h1>
    <?php if ($entry_id && $row): ?>
    <form method="POST" action="edit_entry.php">
        <input type="hidden" name="entry_id" value="<?php echo htmlspecialchars($row['EntryID'], ENT_QUOTES, 'UTF-8'); ?>">
        <div class="form-group">
            <label for="brand_id">BrandID *</label>
            <select class="form-control" name="brand_id" id="brand_id" required>
                <?php
                $stmt = $conn->prepare("SELECT BrandID, BrandName FROM Brands");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($brand = $result->fetch_assoc()) {
                    $selected = $brand['BrandID'] == $row['BrandID'] ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($brand['BrandID'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($brand['BrandName'], ENT_QUOTES, 'UTF-8') . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="model">Model *</label>
            <input type="text" class="form-control" name="model" id="model" value="<?php echo htmlspecialchars($row['Model'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="form-group">
            <label for="serial_number">SerialNumber</label>
            <input type="text" class="form-control" name="serial_number" id="serial_number" value="<?php echo htmlspecialchars($row['SerialNumber'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="form-group">
            <label for="eur">Eur</label>
            <input type="text" class="form-control" name="eur" id="eur" value="<?php echo htmlspecialchars($row['Eur'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="form-group">
            <label for="location_id">LocationID *</label>
            <select class="form-control" name="location_id" id="location_id" required>
                <?php
                $stmt = $conn->prepare("SELECT LocationID, LocationName FROM Locations");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($location = $result->fetch_assoc()) {
                    $selected = $location['LocationID'] == $row['LocationID'] ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($location['LocationID'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($location['LocationName'], ENT_QUOTES, 'UTF-8') . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="device_id">DeviceID *</label>
            <select class="form-control" name="device_id" id="device_id" required>
                <?php
                $stmt = $conn->prepare("SELECT DeviceID, DeviceName FROM Devices");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($device = $result->fetch_assoc()) {
                    $selected = $device['DeviceID'] == $row['DeviceID'] ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($device['DeviceID'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($device['DeviceName'], ENT_QUOTES, 'UTF-8') . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="comments">Comments</label>
            <textarea class="form-control" name="comments" id="comments"><?php echo htmlspecialchars($row['Comments'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>
        <div class="form-group">
            <label for="acquisition_date">AcquisitionDate</label>
            <input type="date" class="form-control" name="acquisition_date" id="acquisition_date" value="<?php echo htmlspecialchars($row['AcquisitionDate'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Entry</button>
        <a href="admin.php" class="btn btn-secondary">Cancel</a>
    </form>
    <?php else: ?>
    <p>No entry found to edit.</p>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="responseModalLabel">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </div>
            <div class="modal-body">
                <p id="modalMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="modalOkButton" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
