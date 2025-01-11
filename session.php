<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['customer_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: signin.php');
        exit();
    }
}

function logout() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    header('Location: signin.php');
    exit();
}
?>