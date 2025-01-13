<?php
require_once 'owner_session.php';
require_once '../db_conn.php';

if (!isset($_SESSION['owner_id'])) {
    header('Location: owner_signin.php');
    exit();
}

$package_id = $_GET['id'];

$stmt = $conn->prepare('DELETE FROM packages WHERE package_id = ?');
$stmt->bind_param('i', $package_id);

if ($stmt->execute()) {
    $_SESSION['package_success'] = 'Package deleted successfully.';
} else {
    $_SESSION['package_error'] = 'Failed to delete package.';
}

header('Location: owner_packages.php');
exit();
?>
