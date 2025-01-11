<?php
require_once 'session.php';
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signup.php');
    exit();
}

try {
    // Validate and sanitize inputs
    $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
    $middleName = filter_input(INPUT_POST, 'middleName', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validation
    $errors = [];

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $errors[] = "Please fill in all required fields";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = "Email address is already registered";
    }
    $stmt->close();

    if (!empty($errors)) {
        $_SESSION['signup_errors'] = $errors;
        $_SESSION['signup_data'] = [
            'firstName' => $firstName,
            'middleName' => $middleName,
            'lastName' => $lastName,
            'email' => $email
        ];
        header('Location: signup.php');
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new customer
    $stmt = $conn->prepare("INSERT INTO customers (first_name, middle_name, last_name, email, password, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $firstName, $middleName, $lastName, $email, $hashedPassword);

    if (!$stmt->execute()) {
        throw new Exception("Failed to create account. Please try again.");
    }

    // Get the new customer_id
    $customer_id = $conn->insert_id;

    // Log the user in
    $_SESSION['customer_id'] = $customer_id;
    $_SESSION['success_message'] = "Account created successfully! Welcome to Food Bonda.";

    // Redirect to homepage
    header('Location: index.php');
    exit();

    header('Location: customer_dashboard.php');
    exit();

} catch (Exception $e) {
    $_SESSION['signup_errors'] = [$e->getMessage()];
    $_SESSION['signup_data'] = [
        'firstName' => $firstName,
        'middleName' => $middleName,
        'lastName' => $lastName,
        'email' => $email
    ];
    header('Location: signup.php');
    exit();
}
?>