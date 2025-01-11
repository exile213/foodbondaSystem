<?php
require_once 'check_auth.php';
if (checkAuthAndRedirect()) {
    header('Location: reservation.php');
    exit();
}
?>