<?php
require_once 'owner_session.php';
require_once '../db_conn.php';

if (!isset($_SESSION['owner_id'])) {
    header('Location: owner_signin.php');
    exit();
}

$owner_id = $_SESSION['owner_id']; // Get the owner ID from the session

// Fetch reservations with status 'pending', 'approved', or 'cancelled'
$sql = "SELECT r.reservation_id, r.first_name, r.middle_name, r.last_name, r.event_date, r.delivery_time, r.delivery_address, r.contact, r.event_type, r.status, pk.package_name, pk.price AS package_price, r.gcash_receipt_path
        FROM reservations r
        JOIN packages pk ON r.package_id = pk.package_id
        WHERE r.status IN ('pending', 'approved', 'cancelled')
        ORDER BY r.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$reservations = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['downpayment'] = $row['package_price'] / 2; // Calculate downpayment as half of the package price
        $reservations[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Reservations</title>
    <link rel="icon" type="image/x-icon" href="../logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="owner_dashboard.css" rel="stylesheet">
</head>

<body>
    <?php include 'owner_navbar.php'; ?>

    <div class="container mt-5">
        <h1>Current Reservations</h1>
        <p class="mb-4">See active and pending reservations</p>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Reservation ID</th>
                        <th>Name</th>
                        <th>Event Date</th>
                        <th>Delivery Time</th>
                        <th>Package</th>
                        <th>Price</th>
                        <th>Downpayment</th>
                        <th>Event</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation['reservation_id']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['middle_name'] . ' ' . $reservation['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['event_date']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['delivery_time']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['package_name']); ?></td>
                        <td>₱<?php echo number_format($reservation['package_price'], 2); ?></td>
                        <td>₱<?php echo number_format($reservation['downpayment'], 2); ?></td>
                        <td><?php echo htmlspecialchars($reservation['event_type']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($reservation['status'])); ?></td>
                        <td>
                            <?php if (!empty($reservation['gcash_receipt_path'])): ?>
                            <a href="../<?php echo htmlspecialchars($reservation['gcash_receipt_path']); ?>" target="_blank" class="btn btn-info btn-sm"><i
                                    class="fas fa-receipt"></i> View Receipt</a>
                            <?php endif; ?>
                            <?php if ($reservation['status'] === 'approved'): ?>
                            <button class="btn btn-success btn-sm" disabled><i class="fas fa-check"></i>
                                Approved</button>
                            <?php else: ?>
                            <a href="approve_reservation.php?id=<?php echo $reservation['reservation_id']; ?>"
                                class="btn btn-success btn-sm action-button"><i class="fas fa-check"></i> Approve</a>
                            <?php endif; ?>
                            <?php if ($reservation['status'] === 'cancelled'): ?>
                            <button class="btn btn-danger btn-sm" disabled><i class="fas fa-times"></i>
                                Cancelled</button>
                            <?php else: ?>
                            <a href="cancel_reservation.php?id=<?php echo $reservation['reservation_id']; ?>"
                                class="btn btn-danger btn-sm action-button"><i class="fas fa-times"></i> Cancel</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status')) {
                const status = urlParams.get('status');
                if (status === 'approved') {
                    alert('Reservation has been approved.');
                } else if (status === 'cancelled') {
                    alert('Reservation has been cancelled.');
                } else if (status === 'error') {
                    alert('An error occurred while processing the reservation.');
                }
            }
        });
    </script>
</body>

</html>
