<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Device | <?php include 'title.inc';?>
</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5"><?php include 'title.inc';?><br />Add Device</h1>
    <form method="POST" action="insert_device.php">
        <div class="form-group">
            <label for="device_name">Device Name</label>
            <input type="text" class="form-control" name="device_name" id="device_name" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Device</button>
    </form>
    <button type="button" onclick="window.location.href='edit_device.php'" class="btn btn-secondary mt-3">Go back</button>

    <h2 class="mt-5">Existing Devices</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Device Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("SELECT * FROM Devices");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['DeviceName'], ENT_QUOTES, 'UTF-8') . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='1'>No devices found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
