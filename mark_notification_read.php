<?php
require_once 'session.php';
require_once 'db_conn.php';

if (!isLoggedIn()) {
    header('Location: signin.php');
    exit();
}

$notification_id = $_GET['id'];
$customer_id = $_SESSION['customer_id'];

$stmt = $conn->prepare('UPDATE notifications SET is_read = 1 WHERE notification_id = ? AND customer_id = ?');
$stmt->bind_param('ii', $notification_id, $customer_id);

if ($stmt->execute()) {
    $_SESSION['notification_success'] = 'Notification marked as read.';
} else {
    $_SESSION['notification_error'] = 'Failed to mark notification as read.';
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
