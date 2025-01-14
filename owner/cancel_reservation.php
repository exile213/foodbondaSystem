<?php
require_once '../db_conn.php';
require_once 'owner_session.php';

if (!isset($_SESSION['owner_id'])) {
    header('Location: owner_signin.php');
    exit();
}

$reservation_id = intval($_GET['id']);

// Fetch customer_id for the reservation before deleting it
$stmt = $conn->prepare('SELECT customer_id FROM reservations WHERE reservation_id = ?');
$stmt->bind_param('i', $reservation_id);
$stmt->execute();
$result = $stmt->get_result();
$reservation = $result->fetch_assoc();

if ($reservation) {
    $customer_id = $reservation['customer_id'];

    // Delete the reservation row
    $stmt = $conn->prepare('DELETE FROM reservations WHERE reservation_id = ?');
    $stmt->bind_param('i', $reservation_id);

    if ($stmt->execute()) {
        // Insert notification
        $message = 'Your reservation has been canceled.';
        $stmt = $conn->prepare('INSERT INTO notifications (customer_id, message) VALUES (?, ?)');
        $stmt->bind_param('is', $customer_id, $message);
        $stmt->execute();

        $_SESSION['reservation_success'] = 'Reservation canceled successfully.';
    } else {
        $_SESSION['reservation_error'] = 'Failed to cancel reservation.';
    }
} else {
    $_SESSION['reservation_error'] = 'Reservation not found.';
}

header('Location: owner_reservations.php');
exit();
?>
