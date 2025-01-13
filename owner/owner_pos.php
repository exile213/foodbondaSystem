<?php
require_once 'owner_session.php';
require_once '../db_conn.php';

if (!isset($_SESSION['owner_id'])) {
    header('Location: owner_signin.php');
    exit();
}

$owner_id = $_SESSION['owner_id']; // Get the owner ID from the session

// Fetch approved reservations from the database
$sql = "SELECT r.reservation_id, r.first_name, r.middle_name, r.last_name, r.event_date, r.delivery_time, r.delivery_address, r.contact, pk.package_name, pk.price AS package_price
        FROM reservations r
        JOIN packages pk ON r.package_id = pk.package_id
        WHERE r.status = 'approved'";
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
    <title>Owner POS</title>
    <link rel="icon" type="image/x-icon" href="../logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="owner_dashboard.css" rel="stylesheet">
    <style>
        .container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .left-side,
        .right-side {
            width: 48%;
        }
    </style>
</head>

<body>
    <?php include 'owner_navbar.php'; ?>

    <div class="container">
        <div class="left-side">
            <h2>Select Reservation</h2>
            <select id="reservationSelect" class="form-select">
                <option value="">Select a reservation</option>
                <?php foreach ($reservations as $reservation): ?>
                <option value="<?php echo $reservation['reservation_id']; ?>">Reservation #<?php echo $reservation['reservation_id']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="right-side">
            <h2>Order Details</h2>
            <div id="orderDetails" class="card">
                <div class="card-body">
                    <p>Select a reservation to view details.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ownerId = <?php echo json_encode($owner_id); ?>; // Pass the owner ID to JavaScript

        document.getElementById('reservationSelect').addEventListener('change', function() {
            var reservationId = this.value;
            if (reservationId) {
                fetch('get_reservation_details.php?id=' + reservationId)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        var orderDetails = document.getElementById('orderDetails');
                        var totalPaid = data.package_price * 0.5; // 50% of the total price
                        var remainingBalance = data.package_price - totalPaid;
                        orderDetails.innerHTML = `
                    <div class="card-body">
                        <h5 class="card-title">Reservation #${data.reservation_id}</h5>
                        <p><strong>Name:</strong> ${data.first_name} ${data.middle_name} ${data.last_name}</p>
                        <p><strong>Event Date:</strong> ${data.event_date}</p>
                        <p><strong>Delivery Time:</strong> ${data.delivery_time}</p>
                        <p><strong>Address:</strong> ${data.delivery_address}</p>
                        <p><strong>Contact:</strong> ${data.contact}</p>
                        <p><strong>Package:</strong> ${data.package_name}</p>
                        <p><strong>Total Price:</strong> ₱${parseFloat(data.package_price).toFixed(2)}</p>
                        <p><strong>Total Paid:</strong> ₱${parseFloat(totalPaid).toFixed(2)}</p>
                        <p><strong>Remaining Balance:</strong> ₱${parseFloat(remainingBalance).toFixed(2)}</p>
                        <div class="mb-3">
                            <label for="receivedAmount" class="form-label">Received Amount</label>
                            <input type="number" class="form-control" id="receivedAmount" step="0.01">
                        </div>
                        <p><strong>Change:</strong> ₱<span id="changeAmount">0.00</span></p>
                        <button class="btn btn-primary" id="processPaymentButton">Process Payment</button>
                    </div>
                `;

                        document.getElementById('receivedAmount').addEventListener('input', function() {
                            var receivedAmount = parseFloat(this.value);
                            var changeAmount = receivedAmount - remainingBalance;
                            document.getElementById('changeAmount').innerText = changeAmount.toFixed(2);
                        });

                        document.getElementById('processPaymentButton').addEventListener('click', function() {
                            var receivedAmount = parseFloat(document.getElementById('receivedAmount')
                                .value);
                            if (receivedAmount >= remainingBalance) {
                                var formData = new URLSearchParams();
                                formData.append('reservation_id', data.reservation_id);
                                formData.append('amount', remainingBalance);
                                formData.append('owner_id', ownerId); // Use the owner ID here

                                fetch('process_payment.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded'
                                        },
                                        body: formData.toString()
                                    })
                                    .then(response => response.json())
                                    .then(result => {
                                        if (result.success) {
                                            alert('Payment processed successfully.');
                                            window.location.href = 'receipt.php?reservation_id=' +
                                                data.reservation_id;
                                        } else {
                                            alert('Failed to process payment.');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('An error occurred while processing the payment.');
                                    });
                            } else {
                                alert('Received amount is less than the remaining balance.');
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while fetching the reservation details.');
                    });
            } else {
                document.getElementById('orderDetails').innerHTML =
                    '<div class="card-body"><p>Select a reservation to view details.</p></div>';
            }
        });
    </script>
</body>

</html>
