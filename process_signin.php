<?php
require_once 'session.php';
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$email || !$password) {
        $_SESSION['error'] = "Please fill in all fields";
        header('Location: signin.php');
        exit();
    }

    $stmt = $conn->prepare("SELECT customer_id, password FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['customer_id'] = $user['customer_id'];
            
            // Redirect to reservation page if that's where they came from
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
                exit();
            }
            
            header('Location: customer_dashboard.php');
            exit();
        }
    }
    
    $_SESSION['error'] = "Invalid email or password";
    header('Location: signin.php');
    exit();
}

header('Location: signin.php');
exit();
?>