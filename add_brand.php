<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Brand | <?php include 'title.inc';?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5"><?php include 'title.inc';?><br />Add Brand</h1>
    <form method="POST" action="insert_brand.php">
        <div class="form-group">
            <label for="brand_name">Brand Name</label>
            <input type="text" class="form-control" name="brand_name" id="brand_name" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Brand</button>
    </form>
    <button type="button" onclick="window.location.href='edit_brand.php'" class="btn btn-secondary mt-3">Go back</button>

    <h2 class="mt-5">Existing Brands</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Brand Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("SELECT * FROM Brands");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['BrandName'], ENT_QUOTES, 'UTF-8') . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='1'>No brands found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
