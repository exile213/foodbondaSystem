<?php
require_once 'owner_session.php';
require_once '../db_conn.php';

if (!isset($_SESSION['owner_id'])) {
    header('Location: owner_signin.php');
    exit();
}

// Fetch packages from the database
$sql = 'SELECT * FROM packages';
$result = $conn->query($sql);
$packages = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Packages</title>
    <link rel="icon" type="image/x-icon" href="../logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="owner_dashboard.css" rel="stylesheet">
</head>

<body>
    <?php include 'owner_navbar.php'; ?>

    <div class="container mt-5">
        <h1><i class="fas fa-box"></i> Packages</h1>
        <p>Manage packages here.</p>
        <a href="add_package.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Add New Package</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Package ID</th>
                    <th>Name</th>
                    <th>Included Dishes</th>
                    <th>Price</th>
                    <th>Additional Dishes Limit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packages as $package): ?>
                <tr>
                    <td><?php echo htmlspecialchars($package['package_id']); ?></td>
                    <td><?php echo htmlspecialchars($package['package_name']); ?></td>
                    <td><?php echo htmlspecialchars($package['included_dishes']); ?></td>
                    <td>₱<?php echo number_format($package['price'], 2); ?></td>
                    <td><?php echo $package['additional_dishes_limit'] == 0 ? 'None' : htmlspecialchars($package['additional_dishes_limit']); ?></td>
                    <td>
                        <a href="edit_package.php?id=<?php echo $package['package_id']; ?>" class="btn btn-warning btn-sm"><i
                                class="fas fa-edit"></i> Edit</a>
                        <a href="delete_package.php?id=<?php echo $package['package_id']; ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this package?');"><i
                                class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
