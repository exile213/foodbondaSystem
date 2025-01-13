<?php
require_once '../db_conn.php';
require_once 'owner_session.php';

// Assuming you have a session variable for the logged-in owner's ID
$owner_id = $_SESSION['owner_id'];

// Fetch reservation history with status 'completed'
$sql = 'SELECT r.reservation_id, r.first_name, r.middle_name, r.last_name, r.event_date, r.delivery_time, r.package_name, r.event_type, r.status, MAX(p.total_price) AS price, SUM(p.amount_paid) AS paid_amount, o.username AS processed_by 
        FROM reservations r 
        LEFT JOIN payment p ON r.reservation_id = p.reservation_id 
        LEFT JOIN owners o ON p.owner_id = o.owner_id 
        WHERE r.status = "completed" AND o.owner_id = ?
        GROUP BY r.reservation_id, r.first_name, r.middle_name, r.last_name, r.event_date, r.delivery_time, r.package_name, r.event_type, r.status, o.username';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $owner_id);
$stmt->execute();
$reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="owner_dashboard.css">
</head>

<body>
    <?php include 'owner_navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4">Reservation History</h1>
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Name</th>
                            <th>Event Date</th>
                            <th>Delivery Time</th>
                            <th>Package</th>
                            <th>Price</th>
                            <th>Paid Amount</th>
                            <th>Downpayment</th>
                            <th>Change</th>
                            <th>Event</th>
                            <th>Status</th>
                            <th>Processed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation): ?>
                        <?php
                        $downpayment = $reservation['price'] * 0.5; // Assuming 50% downpayment
                        $change = $reservation['paid_amount'] - $reservation['price'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['reservation_id']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['first_name'] ?? '') . ' ' . htmlspecialchars($reservation['middle_name'] ?? '') . ' ' . htmlspecialchars($reservation['last_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($reservation['event_date'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($reservation['delivery_time'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($reservation['package_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($reservation['price'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($reservation['paid_amount'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($downpayment); ?></td>
                            <td><?php echo htmlspecialchars($change); ?></td>
                            <td><?php echo htmlspecialchars($reservation['event_type'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($reservation['status'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($reservation['processed_by'] ?? ''); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
