<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php include 'title.inc';?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        th.sortable a {
            color: blue;
            text-decoration: none;
        }
        th.sortable a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('deviceTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                let showRow = false;
                for (let j = 0; j < td.length; j++) {
                    if (td[j].innerText.toLowerCase().includes(filter)) {
                        showRow = true;
                        break;
                    }
                }
                tr[i].style.display = showRow ? '' : 'none';
            }
        }

        function sortTable(n) {
            const table = document.getElementById('deviceTable');
            let rows, switching, i, x, y, shouldSwitch, dir, switchCount = 0;
            switching = true;
            dir = 'asc';

            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName('td')[n];
                    y = rows[i + 1].getElementsByTagName('td')[n];
                    if (dir === 'asc') {
                        if (x.innerText.toLowerCase() > y.innerText.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir === 'desc') {
                        if (x.innerText.toLowerCase() < y.innerText.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchCount++;
                } else {
                    if (switchCount === 0 && dir === 'asc') {
                        dir = 'desc';
                        switching = true;
                    }
                }
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h1 class="mt-5"><?php include 'title.inc';?></h1>
    <form method="GET" action="index.php" class="form-inline mb-3">
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search..." class="form-control mr-2">
        <button type="button" onclick="window.location.href='index.php'" class="btn btn-secondary ml-2">Reset</button>
    </form>
    <a href="add_entry.php" class="btn btn-success mb-3">Add New Entry</a>
    <a href="admin.php" class="btn btn-warning mb-3">Admin</a>
    <table class="table table-bordered" id="deviceTable">
        <thead>
            <tr>
                <th class="sortable"><a href="#" onclick="sortTable(0)">Brand</a></th>
                <th class="sortable"><a href="#" onclick="sortTable(1)">Model</a></th>
                <th>SerialNumber</th>
                <th>Eur</th>
                <th class="sortable"><a href="#" onclick="sortTable(3)">Location</a></th>
                <th class="sortable"><a href="#" onclick="sortTable(4)">Device</a></th>
                <th>Comments</th>
                <th class="sortable"><a href="#" onclick="sortTable(5)">AcquisitionDate</a></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("SELECT Entries.*, Brands.BrandName, Locations.LocationName, Devices.DeviceName
                                    FROM Entries
                                    JOIN Brands ON Entries.BrandID = Brands.BrandID
                                    JOIN Locations ON Entries.LocationID = Locations.LocationID
                                    JOIN Devices ON Entries.DeviceID = Devices.DeviceID");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['BrandName'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($row['Model'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($row['SerialNumber'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($row['Eur'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($row['LocationName'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($row['DeviceName'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($row['Comments'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($row['AcquisitionDate'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>
                            <button class='btn btn-info' onclick='showHistory({$row['EntryID']})'>History</button>
                            <button class='btn btn-primary' onclick='changeLocation({$row['EntryID']}, {$row['LocationID']})'>Change Location</button>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No entries found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- History Modal -->
<div class="modal" id="historyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Location History</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="historyContent">
                <!-- History content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Change Location Modal -->
<div class="modal" id="changeLocationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change Location</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="changeLocationForm">
                    <input type="hidden" name="entry_id" id="entry_id">
                    <div class="form-group">
                        <label for="new_location">New Location</label>
                        <select class="form-control" name="new_location" id="new_location" required>
                            <?php
                            $stmt = $conn->prepare("SELECT LocationID, LocationName FROM Locations");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['LocationID']}'>{$row['LocationName']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Location</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function showHistory(entryID) {
    $.ajax({
        url: 'get_history.php',
        method: 'GET',
        data: { entry_id: entryID },
        success: function(response) {
            $('#historyContent').html(response);
            $('#historyModal').modal('show');
        }
    });
}

function changeLocation(entryID, currentLocationID) {
    $('#entry_id').val(entryID);
    $('#new_location').val(currentLocationID); // Set the current location as the default selection
    $('#changeLocationModal').modal('show');
}

$('#changeLocationForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: 'change_location.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            alert('Location changed successfully');
            $('#changeLocationModal').modal('hide');
            location.reload();
        }
    });
});
</script>
</body>
</html>
