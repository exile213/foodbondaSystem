<?php
require_once 'session.php';
require_once 'db_conn.php';

function checkAuthAndRedirect() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = 'reservation.php';
        $_SESSION['show_login_alert'] = true;
        header('Location: signin.php');
        exit();
    }
    return true;
}
?>