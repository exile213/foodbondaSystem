<?php
require_once '../db_conn.php';
require_once 'owner_session.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/php-error.log'); // Change this to a valid path

$reservation_id = $_POST['reservation_id'];
$amount = $_POST['amount'];
$owner_id = $_POST['owner_id']; // Get the owner ID from the request data

$stmt = $conn->prepare('INSERT INTO payment (reservation_id, amount, owner_id, payment_date, created_at) VALUES (?, ?, ?, NOW(), NOW())');
$stmt->bind_param('idi', $reservation_id, $amount, $owner_id);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $stmt->error;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
