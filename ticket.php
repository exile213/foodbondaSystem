<?php
require_once 'db_conn.php';
require_once 'check_auth.php';

// Check if the reservation ID is set and is a valid number
/*
if (!isset($_GET['id']) && !is_numeric($_GET['id'])) {
    header("Location: customer_dashboard.php");
    exit();
}*/

$id = intval($_GET['id']);

// Fetch reservation details
$sql = 'SELECT * FROM reservations WHERE reservation_id = ? AND customer_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id, $_SESSION['customer_id']);
$stmt->execute();
$result = $stmt->get_result();

/*
if ($result->num_rows === 0) {
    header("Location: customer_dashboard.php");
    exit();
}*/

$reservation = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Receipt</title>
    <link rel="stylesheet" href="style3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .center-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5 center-content">
        <h1 class="mb-4">Reservation Ticket</h1>
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Thank you for your reservation!</h2>
                <p><strong>Reservation ID:</strong> <?php echo htmlspecialchars($reservation['reservation_id']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['middle_name'] . ' ' . $reservation['last_name']); ?></p>
                <p><strong>Event Date:</strong> <?php echo htmlspecialchars($reservation['event_date']); ?></p>
                <p><strong>Delivery Time:</strong> <?php echo htmlspecialchars($reservation['delivery_time']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($reservation['delivery_address']); ?></p>
                <p><strong>Contact:</strong> <?php echo htmlspecialchars($reservation['contact']); ?></p>
                <p><strong>Package:</strong> <?php echo htmlspecialchars($reservation['package_name']); ?></p>
                <p><strong>Dishes:</strong> <?php echo htmlspecialchars($reservation['selected_dishes']); ?></p>
                <p><strong>Price:</strong> ₱<?php echo number_format($reservation['package_price'], 2); ?></p>
                <p><strong>Event:</strong> <?php echo htmlspecialchars($reservation['event_type']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($reservation['payment_method']); ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst(htmlspecialchars($reservation['status'])); ?></p>

                <?php if (!empty($reservation['gcash_receipt_path'])): ?>
                <p><strong>Uploaded GCash Receipt:</strong></p>
                <img src="<?php echo htmlspecialchars($reservation['gcash_receipt_path']); ?>" alt="GCash Receipt" style="max-width: 300px; max-height: 300px;">
                <?php else: ?>
                <p>No GCash receipt uploaded.</p>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="customer_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
                    <?php if ($reservation['status'] === 'pending'): ?>
                    <a href="update_reservation.php?id=<?php echo $id; ?>" class="btn btn-secondary">Update
                        Reservation</a>
                    <form action="cancel_reservation.php" method="POST" style="display:inline;">
                        <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to cancel this reservation?');">Cancel
                            Reservation</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
