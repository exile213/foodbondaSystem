<?php
require_once '../db_conn.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$reservation_id = intval($_GET['id']);

$response = [];

// Fetch reservation details
$sql = 'SELECT * FROM reservations WHERE reservation_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $reservation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $response = $result->fetch_assoc();
} else {
    $response['error'] = 'Reservation not found.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
