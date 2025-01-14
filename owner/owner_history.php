<?php
require_once '../db_conn.php';
require_once 'owner_session.php';

// Assuming you have a session variable for the logged-in owner's ID
$owner_id = $_SESSION['owner_id'];

// Fetch reservation history with status 'completed'
$reservation_sql = 'SELECT r.reservation_id, r.first_name, r.middle_name, r.last_name, r.event_date, r.delivery_time, r.event_type, r.status, pk.package_name, pk.price AS package_price
                    FROM reservations r
                    LEFT JOIN packages pk ON r.package_id = pk.package_id
                    WHERE r.status = "completed"
                    ORDER BY r.created_at DESC';
$reservation_stmt = $conn->prepare($reservation_sql);
$reservation_stmt->execute();
$reservation_history = $reservation_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch transaction history
$transaction_sql = 'SELECT p.reservation_id, p.total_price, p.down_payment, p.leftover_balance, p.amount_paid, p.payment_method, p.payment_date, p.change, o.username AS processed_by
                    FROM payment p
                    LEFT JOIN owners o ON p.owner_id = o.owner_id
                    ORDER BY p.payment_date DESC';
$transaction_stmt = $conn->prepare($transaction_sql);
$transaction_stmt->execute();
$transaction_history = $transaction_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation and Transaction History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="owner_dashboard.css">
</head>

<body>
    <?php include 'owner_navbar.php'; ?>
    <div class="container mt-5">
        <h1>Reservation and Transaction History</h1>
        <p class="mb-4">See completed reservations and transaction history</p>

        <!-- Reservation History -->
        <div class="card mb-5">
            <div class="card-body">
                <h2>Reservation History</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Name</th>
                            <th>Event Date</th>
                            <th>Delivery Time</th>
                            <th>Package</th>
                            <th>Event Type</th>
                            <th>Status</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($reservation_history) > 0): ?>
                        <?php foreach ($reservation_history as $reservation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['reservation_id']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['middle_name'] . ' ' . $reservation['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['event_date']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['delivery_time']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['package_name']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['event_type']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($reservation['status'])); ?></td>
                            <td>₱<?php echo number_format($reservation['package_price'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No reservations found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="card">
            <div class="card-body">
                <h2>Transaction History</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Total Price</th>
                            <th>Down Payment</th>
                            <th>Leftover Balance</th>
                            <th>Amount Paid</th>
                            <th>Change</th>
                            <th>Payment Method</th>
                            <th>Payment Date</th>
                            <th>Processed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($transaction_history) > 0): ?>
                        <?php foreach ($transaction_history as $transaction): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['reservation_id']); ?></td>
                            <td>₱<?php echo number_format($transaction['total_price'], 2); ?></td>
                            <td>₱<?php echo number_format($transaction['down_payment'], 2); ?></td>
                            <td>₱<?php echo number_format($transaction['leftover_balance'], 2); ?></td>
                            <td>₱<?php echo number_format($transaction['amount_paid'], 2); ?></td>
                            <td>₱<?php echo number_format($transaction['change'], 2); ?></td>
                            <td><?php echo htmlspecialchars($transaction['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['payment_date']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['processed_by']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">No transactions found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
