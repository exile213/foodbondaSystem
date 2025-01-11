<?php
require_once 'db_conn.php';
require_once 'session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'];

    $sql = 'DELETE FROM reservations WHERE reservation_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $reservation_id);

    if ($stmt->execute()) {
        $_SESSION['reservation_success'] = 'Your reservation has been successfully cancelled!';
        header('Location: customer_dashboard.php');
        exit();
    } else {
        $_SESSION['reservation_error'] = 'Failed to cancel reservation. Please try again.';
        header("Location: ticket.php?id=$reservation_id");
        exit();
    }
}
?>
