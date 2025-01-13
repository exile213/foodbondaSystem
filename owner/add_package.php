<?php
require_once 'owner_session.php';
require_once '../db_conn.php';

if (!isset($_SESSION['owner_id'])) {
    header('Location: owner_signin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $package_name = $_POST['package_name'];
    $price = $_POST['price'];
    $included_dishes = $_POST['included_dishes'];
    $additional_dishes_limit = $_POST['additional_dishes_limit'];

    $stmt = $conn->prepare('INSERT INTO packages (package_name, price, included_dishes, additional_dishes_limit) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('sdsi', $package_name, $price, $included_dishes, $additional_dishes_limit);

    if ($stmt->execute()) {
        $_SESSION['package_success'] = 'Package added successfully.';
        header('Location: owner_packages.php');
        exit();
    } else {
        $_SESSION['package_error'] = 'Failed to add package.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Package</title>
    <link rel="icon" type="image/x-icon" href="../logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="owner_dashboard.css" rel="stylesheet">
</head>

<body>
    <?php include 'owner_navbar.php'; ?>

    <div class="container mt-5">
        <h1><i class="fas fa-plus"></i> Add New Package</h1>
        <?php if (isset($_SESSION['package_error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['package_error'];
            unset($_SESSION['package_error']); ?>
        </div>
        <?php endif; ?>
        <form action="add_package.php" method="POST">
            <div class="mb-3">
                <label for="package_name" class="form-label">Package Name</label>
                <input type="text" class="form-control" id="package_name" name="package_name" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="included_dishes" class="form-label">Included Dishes</label>
                <textarea class="form-control" id="included_dishes" name="included_dishes" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="additional_dishes_limit" class="form-label">Additional Dishes Limit</label>
                <input type="number" class="form-control" id="additional_dishes_limit" name="additional_dishes_limit">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Package</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
