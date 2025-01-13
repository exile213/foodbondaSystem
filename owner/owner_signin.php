<?php
require_once '../db_conn.php';
require_once 'owner_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT owner_id, password FROM owners WHERE username = ?');
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $owner = $result->fetch_assoc();

    if ($owner) {
        if (password_verify($password, $owner['password'])) {
            $_SESSION['owner_id'] = $owner['owner_id'];
            header('Location: owner_history.php');
            exit();
        } else {
            $_SESSION['signin_error'] = 'Invalid username or password.';
        }
    } else {
        $_SESSION['signin_error'] = 'Invalid username or password.';
    }
    header('Location: owner_signin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Sign In</title>
    <link rel="icon" type="image/x-icon" href="../logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="owner_signin.css" rel="stylesheet">
</head>

<body>
    <div class="signin-container">
        <h2><i class="fas fa-user-shield"></i> Owner Sign In</h2>
        <?php if (isset($_SESSION['signin_error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['signin_error'];
            unset($_SESSION['signin_error']); ?>
        </div>
        <?php endif; ?>
        <form action="owner_signin.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label"><i class="fas fa-user"></i> Username</label>
                <input type="text" class="form-control" id="username" name="username"
                    placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt"></i> Sign In</button>
        </form>
        <div class="mt-3">
            <p class="form-text mt-3">
                Don't have an account? <a href="create_account.php"> Create here</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
