<?php
require_once 'session.php';
require_once 'db_conn.php';
require_once 'check_auth.php';

$id = intval($_GET['id']);

// Fetch reservation details
$sql = 'SELECT * FROM reservations WHERE reservation_id = ? AND customer_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id, $_SESSION['customer_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: customer_dashboard.php');
    exit();
}

$reservation = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Reservation</title>
    <link rel="icon" type="image/x-icon" href="logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="reservation.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <a href="ticket.php?id=<?php echo $id; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Ticket
        </a>

        <form class="reservation-form" action="process_update_reservation.php" method="POST"
            enctype="multipart/form-data" id="reservationForm">
            <h2>Update Your Reservation</h2>
            <input type="hidden" name="reservation_id" value="<?php echo $id; ?>">

            <!-- Personal Information Section -->
            <div class="form-section">
                <h3><i class="fas fa-user"></i> Personal Information</h3>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required
                            maxlength="20" value="<?php echo htmlspecialchars($reservation['first_name']); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name" maxlength="20"
                            value="<?php echo htmlspecialchars($reservation['middle_name']); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required
                            maxlength="20" value="<?php echo htmlspecialchars($reservation['last_name']); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="contact" class="form-label">Contact Number</label>
                        <input type="tel" class="form-control" id="contact" name="contact" required maxlength="11"
                            pattern="[0-9]{11}" value="<?php echo htmlspecialchars($reservation['contact']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required
                            value="<?php echo htmlspecialchars($reservation['email']); ?>">
                    </div>
                </div>
            </div>

            <!-- Event Details Section -->
            <div class="form-section">
                <h3><i class="fas fa-calendar-alt"></i> Event Details</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="event_date" class="form-label">Event Date</label>
                        <input type="date" class="form-control" id="event_date" name="event_date" required
                            min="<?php echo date('Y-m-d', strtotime('+3 days')); ?>" value="<?php echo htmlspecialchars($reservation['event_date']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="delivery_time" class="form-label">Delivery Time</label>
                        <input type="time" class="form-control" id="delivery_time" name="delivery_time" required
                            value="<?php echo htmlspecialchars($reservation['delivery_time']); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Delivery Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2" required><?php echo htmlspecialchars($reservation['delivery_address']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="event" class="form-label">Event Type</label>
                    <select class="form-select" id="event" name="event" required>
                        <option value="">Select event type</option>
                        <option value="wedding" <?php echo $reservation['event_type'] === 'wedding' ? 'selected' : ''; ?>>Wedding</option>
                        <option value="birthday" <?php echo $reservation['event_type'] === 'birthday' ? 'selected' : ''; ?>>Birthday</option>
                        <option value="christening" <?php echo $reservation['event_type'] === 'christening' ? 'selected' : ''; ?>>Christening</option>
                        <option value="thanksgiving" <?php echo $reservation['event_type'] === 'thanksgiving' ? 'selected' : ''; ?>>Thanksgiving</option>
                        <option value="fiesta" <?php echo $reservation['event_type'] === 'fiesta' ? 'selected' : ''; ?>>Fiesta</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check-circle"></i> Update Reservation
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
