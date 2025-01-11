<?php
require_once '../db_conn.php';

$username = 'owner_username';
$password = password_hash('owner_password', PASSWORD_DEFAULT);
$first_name = 'OwnerFirstName';
$last_name = 'OwnerLastName';
$email = 'owner@example.com';
$phone = '1234567890';

$stmt = $conn->prepare('INSERT INTO owners (username, password, first_name, last_name, email, phone) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->bind_param('ssssss', $username, $password, $first_name, $last_name, $email, $phone);

if ($stmt->execute()) {
    echo 'Owner inserted successfully.';
} else {
    echo 'Error: ' . $stmt->error;
}
?>
