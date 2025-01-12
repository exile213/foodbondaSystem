<?php
require_once 'owner_session.php';
require_once '../db_conn.php';

if (!isset($_SESSION['owner_id'])) {
    header('Location: owner_signin.php');
    exit();
}

// Fetch reservations from the database
$sql = 'SELECT * FROM reservations';
$result = $conn->query($sql);
$reservations = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Reservations - FOOD BONDA Catering Service</title>
    <link rel="icon" type="image/x-icon" href="../logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="owner_dashboard.css" rel="stylesheet">
</head>

<body>
    <?php include 'owner_navbar.php'; ?>

    <div class="container mt-5">
        <h1><i class="fas fa-calendar-alt"></i> Reservations</h1>
        <p>Manage reservations here.</p>

        <?php if (isset($_SESSION['reservation_success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['reservation_success'];
            unset($_SESSION['reservation_success']); ?>
        </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['reservation_error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['reservation_error'];
            unset($_SESSION['reservation_error']); ?>
        </div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Name</th>
                    <th>Event Date</th>
                    <th>Delivery Time</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Package</th>
                    <th>Price</th>
                    <th>Event</th>
                    <th>Payment Method</th>
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
                    <td><?php echo htmlspecialchars($reservation['delivery_address']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['contact']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['package_name']); ?></td>
                    <td>₱<?php echo number_format($reservation['package_price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($reservation['event_type']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['payment_method']); ?></td>
                    <td><?php echo ucfirst(htmlspecialchars($reservation['status'])); ?></td>
                    <td>
                        <?php if (!empty($reservation['gcash_receipt_path'])): ?>
                        <a href="../<?php echo htmlspecialchars($reservation['gcash_receipt_path']); ?>" target="_blank" class="btn btn-info btn-sm"><i
                                class="fas fa-receipt"></i> View Receipt</a>
                        <?php endif; ?>
                        <?php if ($reservation['status'] === 'approved'): ?>
                        <button class="btn btn-success btn-sm" disabled><i class="fas fa-check"></i> Approved</button>
                        <?php else: ?>
                        <a href="approve_reservation.php?id=<?php echo $reservation['reservation_id']; ?>"
                            class="btn btn-success btn-sm action-button"><i class="fas fa-check"></i> Approve</a>
                        <?php endif; ?>
                        <?php if ($reservation['status'] === 'canceled'): ?>
                        <button class="btn btn-danger btn-sm" disabled><i class="fas fa-times"></i> Canceled</button>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.action-button').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                button.classList.add('disabled');
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                window.location.href = button.getAttribute('href');
            });
        });
    </script>
</body>

</html>
