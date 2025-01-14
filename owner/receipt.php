<?php
require_once '../db_conn.php';
require_once 'owner_session.php';

$reservation_id = intval($_GET['reservation_id']);

// Fetch reservation details
$sql = 'SELECT r.*, pk.package_name FROM reservations r JOIN packages pk ON r.package_id = pk.package_id WHERE r.reservation_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $reservation_id);
$stmt->execute();
$reservation = $stmt->get_result()->fetch_assoc();

// Fetch payment details
$sql = 'SELECT p.*, o.username AS cashier_name FROM payment p JOIN owners o ON p.owner_id = o.owner_id WHERE p.reservation_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $reservation_id);
$stmt->execute();
$payment = $stmt->get_result()->fetch_assoc();

// Update reservation status to completed
$update_sql = 'UPDATE reservations SET status = "completed" WHERE reservation_id = ?';
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param('i', $reservation_id);
$update_stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="owner_dashboard.css">
    <style>
        .center-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }

        .card {
            width: 50%;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .printableArea,
            .printableArea * {
                visibility: visible;
            }

            .printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php include 'owner_navbar.php'; ?>
    <div class="container mt-5 center-content printableArea">
        <h1 class="mb-4">Payment Receipt</h1>
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Payment Details</h2>
                <p><strong>Reservation ID:</strong> <?php echo htmlspecialchars($reservation['reservation_id']); ?></p>
                <p><strong>Processed By:</strong> <?php echo htmlspecialchars($payment['cashier_name']); ?></p>
                <hr>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['middle_name'] . ' ' . $reservation['last_name']); ?></p>
                <p><strong>Event Date:</strong> <?php echo htmlspecialchars($reservation['event_date']); ?></p>
                <p><strong>Delivery Time:</strong> <?php echo htmlspecialchars($reservation['delivery_time']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($reservation['delivery_address']); ?></p>
                <p><strong>Contact:</strong> <?php echo htmlspecialchars($reservation['contact']); ?></p>
                <p><strong>Package:</strong> <?php echo htmlspecialchars($reservation['package_name']); ?></p>
                <p><strong>Total Price:</strong> ₱<?php echo number_format($payment['total_price'], 2); ?></p>
                <p><strong>Down Payment:</strong> ₱<?php echo number_format($payment['down_payment'], 2); ?></p>
                <p><strong>Amount Paid:</strong> ₱<?php echo number_format($payment['amount_paid'], 2); ?></p>
                <p><strong>Leftover Balance:</strong> ₱<?php echo number_format($payment['leftover_balance'], 2); ?></p>
                <p><strong>Change:</strong> ₱<?php echo number_format($payment['change'], 2); ?></p>
                <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($payment['payment_date']); ?></p>
                <div class="mt-4">
                    <a href="owner_history.php" class="btn btn-primary">Back to Dashboard</a>
                    <button onclick="window.print()" class="btn btn-secondary">Print Receipt</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
