<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Devices | <?php include 'title.inc';?>
</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <h1 class="mt-5"><?php include 'title.inc';?><br />Edit Devices</h1>
    <button type="button" onclick="window.location.href='add_device.php'" class="btn btn-primary mb-3">Add Device</button>
    <button type="button" onclick="window.location.href='admin.php'" class="btn btn-secondary mb-3">Go back</button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Device Name</th>
                <th>Actions</th>
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
                        <td><button type='button' class='btn btn-primary' data-toggle='modal' data-target='#editModal' data-id='" . htmlspecialchars($row['DeviceID'], ENT_QUOTES, 'UTF-8') . "' data-name='" . htmlspecialchars($row['DeviceName'], ENT_QUOTES, 'UTF-8') . "'>Edit</button></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No devices found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Device</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="update_device.php">
                    <input type="hidden" name="device_id" id="device_id">
                    <div class="form-group">
                        <label for="brand_name">Device Name</label>
                        <input type="text" class="form-control" name="device_name" id="device_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var deviceId = button.data('id');
        var deviceName = button.data('name');

        var modal = $(this);
        modal.find('.modal-title').text('Edit Device: ' + deviceName);
        modal.find('#device_id').val(deviceId);
        modal.find('#device_name').val(deviceName);
    });
</script>
</body>
</html>
