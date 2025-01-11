<?php
require_once 'owner_session.php';

session_destroy();
header('Location: owner_signin.php');
exit();
?>
