<?php
require_once 'owner_session.php';
require_once '../db_conn.php';

if (!isset($_SESSION['owner_id'])) {
    header('Location: owner_signin.php');
    exit();
}

$owner_id = $_SESSION['owner_id'];
$stmt = $conn->prepare('SELECT first_name, last_name FROM owners WHERE owner_id = ?');
$stmt->bind_param('i', $owner_id);
$stmt->execute();
$result = $stmt->get_result();
$owner = $result->fetch_assoc();

$owner_name = $owner ? $owner['first_name'] . ' ' . $owner['last_name'] : 'Owner';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="owner_dashboard.css" rel="stylesheet">
</head>

<body>
    <?php include 'owner_navbar.php'; ?>

    <div class="container mt-5">
        <h1><i class="fas fa-tachometer-alt"></i> Welcome, <?php echo htmlspecialchars($owner_name); ?>!</h1>
        <p>Manage your catering service here.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
