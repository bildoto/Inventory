<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Edit Entries | <?php include 'title.inc';?>
</title>
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
    <h1 class="mt-5"><?php include 'title.inc';?><br />Admin - Edit Entries</h1>
    <form method="GET" action="index.php" class="form-inline mb-3">
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search.." class="form-control mr-2">
        <button type="button" onclick="window.location.href='admin.php'" class="btn btn-secondary ml-2">Reset</button>
    </form>
    <button type="button" onclick="window.location.href='index.php'" class="btn btn-secondary mb-3">Return to Index</button>
    <button type="button" onclick="window.location.href='edit_brand.php'" class="btn btn-primary mb-3">Edit Brands</button>
    <button type="button" onclick="window.location.href='edit_location.php'" class="btn btn-primary mb-3">Edit Locations</button>
    <button type="button" onclick="window.location.href='edit_device.php'" class="btn btn-primary mb-3">Edit Devices</button>
    <table class="table table-bordered" id="deviceTable">
        <thead>
            <tr>
                <th class="sortable"><a href="#" onclick="sortTable(0)">Brand</a></th>
                <th class="sortable"><a href="#" onclick="sortTable(1)">Model</a></th>
                <th>SerialNumber</th>
                <th>Eur</th>
                <th class="sortable"><a href="#" onclick="sortTable(2)">Location</a></th>
                <th class="sortable"><a href="#" onclick="sortTable(3)">Device</a></th>
                <th>Comments</th>
                <th>AcquisitionDate</th>
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
                        <td><a href='edit_entry.php?id=" . htmlspecialchars($row['EntryID'], ENT_QUOTES, 'UTF-8') . "' class='btn btn-primary'>Edit</a></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No entries found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
