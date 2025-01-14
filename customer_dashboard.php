<?php
require_once 'session.php';
requireLogin();
require_once 'db_conn.php';

// Fetch customer data
$customer_id = $_SESSION['customer_id'];
$stmt = $conn->prepare('SELECT * FROM customers WHERE customer_id = ?');
$stmt->bind_param('i', $customer_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-user-circle fa-4x mb-3"></i>
                        <h5 class="card-title"><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars($customer['email']); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Reservations</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Package</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch recent reservations for the customer that are not completed
                                    $stmt = $conn->prepare("SELECT r.reservation_id, r.event_date, r.status, p.amount_paid, pk.package_name, pk.price 
                                                            FROM reservations r 
                                                            LEFT JOIN payment p ON r.reservation_id = p.reservation_id 
                                                            LEFT JOIN packages pk ON r.package_id = pk.package_id
                                                            WHERE r.customer_id = ? AND r.status != 'completed'
                                                            ORDER BY r.created_at DESC 
                                                            LIMIT 5");
                                    $stmt->bind_param("i", $customer_id);
                                    $stmt->execute();
                                    $reservations = $stmt->get_result();
                                    
                                    if ($reservations->num_rows > 0):
                                        while ($reservation = $reservations->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($reservation['event_date']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['package_name']); ?></td>
                                        <td>₱<?php echo number_format($reservation['price'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $reservation['status'] === 'cancelled' ? 'danger' : 'warning'; ?>">
                                                <?php echo ucfirst(htmlspecialchars($reservation['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="ticket.php?id=<?php echo $reservation['reservation_id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <?php if ($reservation['status'] === 'pending'): ?>
                                            <a href="update_reservation.php?id=<?php echo $reservation['reservation_id']; ?>"
                                                class="btn btn-sm btn-secondary">
                                                <i class="fas fa-edit"></i> Update
                                            </a>
                                            <form action="cancel_reservation.php" method="POST"
                                                style="display:inline;">
                                                <input type="hidden" name="reservation_id"
                                                    value="<?php echo $reservation['reservation_id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to cancel this reservation?');">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No recent reservations found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Completed Reservations</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Package</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch completed reservations for the customer
                                    $stmt = $conn->prepare("SELECT r.reservation_id, r.event_date, r.status, p.amount_paid, pk.package_name, pk.price 
                                                            FROM reservations r 
                                                            LEFT JOIN payment p ON r.reservation_id = p.reservation_id 
                                                            LEFT JOIN packages pk ON r.package_id = pk.package_id
                                                            WHERE r.customer_id = ? AND r.status = 'completed'
                                                            ORDER BY r.event_date DESC");
                                    $stmt->bind_param("i", $customer_id);
                                    $stmt->execute();
                                    $completed_reservations = $stmt->get_result();
                                    
                                    if ($completed_reservations->num_rows > 0):
                                        while ($reservation = $completed_reservations->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($reservation['event_date']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['package_name']); ?></td>
                                        <td>₱<?php echo number_format($reservation['price'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-success">
                                                <?php echo ucfirst(htmlspecialchars($reservation['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="ticket.php?id=<?php echo $reservation['reservation_id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No completed reservations found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
