<?php
require_once 'header.php';
require_once 'db_conn.php';

$sql = 'SELECT * FROM packages';
$result = $conn->query($sql);
$packages = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOOD BONDA Catering Service</title>
    <link rel="icon" type="image/x-icon" href="logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="main.css" rel="stylesheet">
    <style>
        .card-custom {
            margin-right: 50px;
            width: 18rem;
            /* Set the desired width */
            height: 22rem;
            /* Set the desired height */
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
        echo htmlspecialchars($_SESSION['success_message']);
        unset($_SESSION['success_message']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div id="intro" class="section light-blue">
        <div class="container">
            <h1>Welcome to FOOD BONDA Catering Service</h1>
            <p>Your trusted partner for delicious catering solutions.</p>
        </div>
    </div>

    <div id="menu" class="section light-gray">
        <div class="container">
            <h2 class="text-center mb-5">Our Menu Packages</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                <?php foreach ($packages as $package): ?>
                <div class="col">
                    <div class="card card-custom h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($package['package_name']); ?></h5>
                            <p class="card-text"><strong>Price:</strong> ₱<?php echo number_format($package['price'], 2); ?></p>
                            <a href="reservation.php?package=<?php echo urlencode($package['package_name']); ?>" class="btn btn-primary">Reserve
                                Now</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reservationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Would you like to reserve?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <a href="check_reservation.php" class="btn btn-primary">Yes</a>
                </div>
            </div>
        </div>
    </div>

    <div id="rules" class="section light-blue">
        <div class="container">
            <h2>Rules and Regulations</h2>
            <ul>
                <li>Reservations must be made at least 3 to 4 days in advance.</li>
                <li>Cancellations are allowed with a full refund if made at least 24 hours in advance.</li>
                <li>You may change the date and time of your reservation.</li>
                <li>A down payment of at least 50% is required to confirm your reservation.</li>
            </ul>
        </div>
    </div>

    <div id="faqs" class="section light-gray">
        <div class="container">
            <h2>Frequently Asked Questions (FAQs)</h2>
            <br>
            <h4>1. What happens if I need to cancel my reservation?</h4>
            <p>You can cancel your reservation with a full refund if done at least 24 hours in advance.
                Cancellations
                made after 24 hours will not be refunded.</p>

            <h4>2. Can I change my reservation date and time?</h4>
            <p>Yes, you can change your reservation date and time. Please contact us at least 24 hours in
                advance to
                make changes.</p>

            <h4>3. How do I make the down payment?</h4>
            <p>You can make the down payment online or in person. Please refer to the payment instructions
                provided
                during the reservation process.</p>

            <h4>4. Is my down payment refundable?</h4>
            <p>The down payment is non-refundable if you cancel less than 24 hours before your reservation.
                Please
                ensure your plans are final before reserving.</p>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <img src="logo.jpg" alt="FOODBONDA_logo">
                    <span>FOOD BONDA</span>
                </div>
                <div class="contact-info">
                    <div class="contact-icons">
                        <i class="fas fa-phone"></i>
                        <span>+9300712088</span>
                    </div>
                    <div class="contact-icons">
                        <i class="fab fa-facebook"></i>
                        <span>FOOD BONDA (FB PAGE)/Jillian Christine Yanyan Bonda</span>
                    </div>
                </div>
                <p class="copyright">&copy; 2024 Food Bonda Reservation System</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
