<?php
require_once '../db_conn.php';
require_once 'owner_session.php';

if (!isset($_SESSION['owner_id'])) {
    header('Location: owner_signin.php');
    exit();
}

$reservation_id = $_GET['id'];

$stmt = $conn->prepare('UPDATE reservations SET status = ? WHERE reservation_id = ?');
$status = 'cancelled';
$stmt->bind_param('si', $status, $reservation_id);

if ($stmt->execute()) {
    // Fetch customer_id for the reservation
    $stmt = $conn->prepare('SELECT customer_id FROM reservations WHERE reservation_id = ?');
    $stmt->bind_param('i', $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reservation = $result->fetch_assoc();
    $customer_id = $reservation['customer_id'];

    // Insert notification
    $message = 'Your reservation has been canceled.';
    $stmt = $conn->prepare('INSERT INTO notifications (customer_id, message) VALUES (?, ?)');
    $stmt->bind_param('is', $customer_id, $message);
    $stmt->execute();

    $_SESSION['reservation_success'] = 'Reservation canceled successfully.';
} else {
    $_SESSION['reservation_error'] = 'Failed to cancel reservation.';
}

header('Location: owner_reservations.php');
exit();
?>
