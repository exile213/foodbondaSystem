<?php
require_once 'db_conn.php';
require_once 'session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $event_date = $_POST['event_date'];
    $delivery_time = $_POST['delivery_time'];
    $delivery_address = $_POST['address'];
    $event_type = $_POST['event'];

    $sql = 'UPDATE reservations SET first_name = ?, middle_name = ?, last_name = ?, contact = ?, email = ?, event_date = ?, delivery_time = ?, delivery_address = ?, event_type = ? WHERE reservation_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssssi', $first_name, $middle_name, $last_name, $contact, $email, $event_date, $delivery_time, $delivery_address, $event_type, $reservation_id);

    if ($stmt->execute()) {
        $_SESSION['reservation_success'] = 'Your reservation has been successfully updated!';
        header("Location: ticket.php?id=$reservation_id");
        exit();
    } else {
        $_SESSION['reservation_error'] = 'Failed to update reservation. Please try again.';
        header("Location: reservation.php?update=1&id=$reservation_id");
        exit();
    }
}
?>
