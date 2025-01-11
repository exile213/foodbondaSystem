<?php
require_once '../db_conn.php';
require_once 'owner_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    $stmt = $conn->prepare('INSERT INTO owners (username, password, first_name, last_name, email) VALUES (?, ?, ?, ?, ?)');
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param('sssss', $username, $password, $first_name, $last_name, $email);

    if ($stmt->execute()) {
        $_SESSION['account_success'] = 'Owner account created successfully.';
        header('Location: owner_signin.php');
        exit();
    } else {
        $_SESSION['account_error'] = 'Error: ' . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Owner Account</title>
    <link rel="icon" type="image/x-icon" href="../logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="owner_signin.css" rel="stylesheet">
</head>

<body>
    <div class="signin-container">
        <h2><i class="fas fa-user-plus"></i> Create Owner Account</h2>
        <?php if (isset($_SESSION['account_error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['account_error'];
            unset($_SESSION['account_error']); ?>
        </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['account_success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['account_success'];
            unset($_SESSION['account_success']); ?>
        </div>
        <?php endif; ?>
        <form action="create_account.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label"><i class="fas fa-user"></i> Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username"
                    required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password"
                    required>
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label"><i class="fas fa-id-badge"></i> First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                    placeholder="Enter your first name" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label"><i class="fas fa-id-badge"></i> Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                    placeholder="Enter your last name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="Enter your email address" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-user-plus"></i> Create
                Account</button>
            <p class="form-text mt-3">
                Already have an account? <a href="owner_signin.php"> Sign-in here</a>
            </p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
