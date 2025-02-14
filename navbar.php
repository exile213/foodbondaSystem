<?php
require_once 'session.php';
require_once 'db_conn.php';

$notifications = [];
if (isLoggedIn()) {
    $customer_id = $_SESSION['customer_id'];
    $stmt = $conn->prepare('SELECT * FROM notifications WHERE customer_id = ? AND is_read = 0 ORDER BY created_at DESC LIMIT 5');
    $stmt->bind_param('i', $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
}
?>
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
                <?php if (isLoggedIn()): ?>
                <div class="d-flex align-items-center">
                    <div class="notification-bell me-3 position-relative">
                        <a href="#" class="text-white" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <?php if (count($notifications) > 0): ?>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo count($notifications); ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Notifications</h6>
                            <?php if (count($notifications) > 0): ?>
                            <?php foreach ($notifications as $notification): ?>
                            <div class="dropdown-item d-flex justify-content-between align-items-center">
                                <span><?php echo htmlspecialchars($notification['message']); ?></span>
                                <a href="mark_notification_read.php?id=<?php echo $notification['notification_id']; ?>"
                                    class="btn btn-sm btn-primary">Mark as Read</a>
                            </div>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <a class="dropdown-item" href="#">No new notifications</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="customer_dashboard.php" class="btn btn-outline-light me-2">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="logout.php" class="btn btn-light">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
                <?php else: ?>
                <a href="signin.php" class="btn btn-outline-light">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </a>
                <a href="signup.php" class="btn btn-light">
                    <i class="fas fa-user-plus"></i> Sign Up
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
