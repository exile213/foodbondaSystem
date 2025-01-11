<?php
require_once 'session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="icon" type="image/x-icon" href="logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
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


    <div class="auth-container">
        <form class="auth-form" action="process_signup.php" method="POST">
            <h2>Create Account</h2>

            <?php if (isset($_SESSION['signup_errors'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($_SESSION['signup_errors'] as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['signup_errors']); ?>
            <?php endif; ?>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="firstName" name="firstName" 
                    placeholder="First Name" required maxlength="50"
                    value="<?php echo isset($_SESSION['signup_data']['firstName']) ? htmlspecialchars($_SESSION['signup_data']['firstName']) : ''; ?>">
                <label for="firstName">First Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="middleName" name="middleName" 
                    placeholder="Middle Name" maxlength="50"
                    value="<?php echo isset($_SESSION['signup_data']['middleName']) ? htmlspecialchars($_SESSION['signup_data']['middleName']) : ''; ?>">
                <label for="middleName">Middle Name (Optional)</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="lastName" name="lastName" 
                    placeholder="Last Name" required maxlength="50"
                    value="<?php echo isset($_SESSION['signup_data']['lastName']) ? htmlspecialchars($_SESSION['signup_data']['lastName']) : ''; ?>">
                <label for="lastName">Last Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" 
                    placeholder="name@example.com" required maxlength="100"
                    value="<?php echo isset($_SESSION['signup_data']['email']) ? htmlspecialchars($_SESSION['signup_data']['email']) : ''; ?>">
                <label for="email">Email address</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" 
                    placeholder="Password" required minlength="8">
                <label for="password">Password</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" 
                    placeholder="Confirm Password" required minlength="8">
                <label for="confirmPassword">Confirm Password</label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Sign Up
            </button>

            <p class="form-text">
                Already have an account? <a href="signin.php">Sign in here</a>
            </p>
        </form>
    </div>

    <?php unset($_SESSION['signup_data']); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>