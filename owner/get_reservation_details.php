<?php
require_once '../db_conn.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Reservation ID is required']);
    exit();
}

$reservation_id = intval($_GET['id']);

// Fetch reservation details
$sql = 'SELECT r.reservation_id, r.first_name, r.middle_name, r.last_name, r.event_date, r.delivery_time, r.delivery_address, r.contact, pk.package_name, pk.price AS package_price
        FROM reservations r
        JOIN packages pk ON r.package_id = pk.package_id
        WHERE r.reservation_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $reservation_id);
$stmt->execute();
$reservation = $stmt->get_result()->fetch_assoc();

if (!$reservation) {
    echo json_encode(['error' => 'Reservation not found']);
    exit();
}

echo json_encode($reservation);
?>
