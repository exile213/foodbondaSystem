<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500&display=swap"
        rel="stylesheet">
    <link href="signup.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="logo.jpg" alt="FOODBONDA_logo">
                FOOD BONDA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#intro"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#menu"><i class="fas fa-utensils"></i> Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#rules"><i class="fas fa-clipboard-list"></i> Rules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#faqs"><i class="fas fa-question-circle"></i> FAQs</a>
                    </li>
                </ul>
                <div class="auth-buttons">
                    <a href="signin.php" class="btn btn-outline-light"><i class="fas fa-sign-in-alt"></i> Sign In</a>
                    <a href="signup.php" class="btn btn-light active"><i class="fas fa-user-plus"></i> Sign Up</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Reservation Terms and Policies</h1>
                <ol>
                    <li><strong>Advance Reservations:</strong> Reservations must be made at least 3 to 4 days in advance
                        to ensure availability.</li>
                    <li><strong>Cancellations:</strong> Cancellations are permitted with a full refund if made at least
                        24 hours prior to the scheduled reservation.</li>
                    <li><strong>Changes to Reservations:</strong> You may change the date and time of your reservation
                        only if requested at least 24 hours in advance. Once your reservation is approved by the owner,
                        changes or cancellations will not be allowed.</li>
                    <li><strong>Down Payment:</strong> A minimum down payment of 50% is required to confirm your
                        reservation. This payment secures your booking and is non-refundable in the event of a late
                        cancellation (less than 24 hours).</li>
                </ol>
                <div class="mt-4">
                    <a href="signup.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to Sign Up</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
