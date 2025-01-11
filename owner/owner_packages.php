<?php
require_once 'owner_session.php';

if (!isset($_SESSION['owner_id'])) {
    header('Location: owner_signin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Packages - FOOD BONDA Catering Service</title>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
