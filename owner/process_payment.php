<?php
require_once '../db_conn.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

$reservation_id = intval($_POST['reservation_id']);
$amount = floatval($_POST['amount']);
$owner_id = intval($_POST['owner_id']);

// Fetch reservation details to get the total price and calculate the remaining balance
$sql = 'SELECT pk.price AS package_price
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

$total_price = $reservation['package_price'];
$down_payment = $total_price * 0.5;
$leftover_balance = $total_price - $down_payment;

// Calculate change
$change = $amount - $leftover_balance;

// Insert payment details into the payment table
$sql = 'INSERT INTO payment (reservation_id, owner_id, total_price, down_payment, leftover_balance, amount_paid, payment_method, payment_date, `change`, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, NOW())';
$stmt = $conn->prepare($sql);
$payment_method = 'Full Payment'; // Assuming full payment for the remaining balance
$stmt->bind_param('iiddddsd', $reservation_id, $owner_id, $total_price, $down_payment, $leftover_balance, $amount, $payment_method, $change);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to process payment']);
}
?>
