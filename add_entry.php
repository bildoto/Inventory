<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Entry | <?php include 'title.inc';?>
</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5"><?php include 'title.inc';?><br />Add New Entry</h1>
    <form method="POST" action="save_entry.php">
        <div class="form-group">
            <label for="brand_id">BrandID *</label>
            <select class="form-control" name="brand_id" id="brand_id" required>
                <?php
                $stmt = $conn->prepare("SELECT BrandID, BrandName FROM Brands");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['BrandID'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['BrandName'], ENT_QUOTES, 'UTF-8') . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="model">Model *</label>
            <input type="text" class="form-control" name="model" id="model" required>
        </div>
        <div class="form-group">
            <label for="serial_number">SerialNumber</label>
            <input type="text" class="form-control" name="serial_number" id="serial_number">
        </div>
        <div class="form-group">
            <label for="eur">Eur</label>
            <input type="text" class="form-control" name="eur" id="eur">
        </div>
        <div class="form-group">
            <label for="location_id">LocationID *</label>
            <select class="form-control" name="location_id" id="location_id" required>
                <?php
                $stmt = $conn->prepare("SELECT LocationID, LocationName FROM Locations");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['LocationID'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['LocationName'], ENT_QUOTES, 'UTF-8') . "</option>";
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
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['DeviceID'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['DeviceName'], ENT_QUOTES, 'UTF-8') . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="comments">Comments</label>
            <textarea class="form-control" name="comments" id="comments"></textarea>
        </div>
        <div class="form-group">
            <label for="acquisition_date">AcquisitionDate</label>
            <input type="date" class="form-control" name="acquisition_date" id="acquisition_date">
        </div>
        <button type="submit" class="btn btn-success">Add Entry</button>
        <a href="admin.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
