<?php
session_start();
require_once 'db_conn.php';
require_once 'check_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: reservation.php');
    exit();
}

try {
    // Verify database connection
    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    // Verify customer authentication
    if (!isset($_SESSION['customer_id'])) {
        throw new Exception('User not authenticated');
    }

    // Validate and sanitize inputs
    $customer_id = $_SESSION['customer_id'];
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $middle_name = htmlspecialchars(trim($_POST['middle_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $contact = htmlspecialchars(trim($_POST['contact']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $event_date = htmlspecialchars(trim($_POST['event_date']));
    $delivery_time = htmlspecialchars(trim($_POST['delivery_time']));
    $delivery_address = htmlspecialchars(trim($_POST['address']));
    $event_type = htmlspecialchars(trim($_POST['event']));
    $package_name = htmlspecialchars(trim($_POST['package']));
    $selected_dishes = htmlspecialchars(trim($_POST['selectedDishes']));
    $package_price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $payment_method = htmlspecialchars(trim($_POST['payment_method']));

    // Validate required fields
    if (!$first_name || !$last_name || !$contact || !$email || !$event_date || !$delivery_time || !$delivery_address || !$event_type || !$package_name || !$package_price || !$payment_method) {
        throw new Exception('All required fields must be filled');
    }

    // Validate contact number format
    if (!preg_match('/^[0-9]{11}$/', $contact)) {
        throw new Exception('Invalid contact number format');
    }

    // Validate email
    if (!$email) {
        throw new Exception('Invalid email address');
    }

    // Validate event date (must be at least 3 days from now)
    $min_date = date('Y-m-d', strtotime('+3 days'));
    if ($event_date < $min_date) {
        throw new Exception('Event date must be at least 3 days from today');
    }

    // Handle additional dishes
    if (isset($_POST['additionalDishes']) && is_array($_POST['additionalDishes'])) {
        $additional_dishes = array_map('htmlspecialchars', $_POST['additionalDishes']);
        $selected_dishes .= ', PLUS ADDITIONAL DISHES: ' . implode(', ', $additional_dishes);
    }

    // Handle file upload
    $gcash_receipt_path = null;
    if ($payment_method === 'Downpayment 50%' && isset($_FILES['gcash_receipt'])) {
        $upload_dir = 'uploads/receipts/';

        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                throw new Exception('Failed to create upload directory');
            }
        }

        // Validate file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($_FILES['gcash_receipt']['type'], $allowed_types)) {
            throw new Exception('Invalid file type. Only JPG, PNG, and GIF are allowed');
        }

        if ($_FILES['gcash_receipt']['size'] > $max_size) {
            throw new Exception('File size exceeds limit');
        }

        $file_extension = pathinfo($_FILES['gcash_receipt']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $target_path = $upload_dir . $file_name;

        if (!move_uploaded_file($_FILES['gcash_receipt']['tmp_name'], $target_path)) {
            throw new Exception('Failed to upload receipt');
        }
        $gcash_receipt_path = $target_path;
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into reservations table
        $sql = "INSERT INTO reservations (
            customer_id, first_name, middle_name, last_name, contact, email,
            event_date, delivery_time, delivery_address, event_type,
            package_name, selected_dishes, package_price, payment_method,
            gcash_receipt_path, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        $stmt->bind_param('isssssssssssiss', $customer_id, $first_name, $middle_name, $last_name, $contact, $email, $event_date, $delivery_time, $delivery_address, $event_type, $package_name, $selected_dishes, $package_price, $payment_method, $gcash_receipt_path);

        if (!$stmt->execute()) {
            throw new Exception('Failed to save reservation: ' . $stmt->error);
        }

        // Get the new reservation_id
        $reservation_id = $stmt->insert_id;

        // Commit transaction
        $conn->commit();

        $_SESSION['reservation_success'] = 'Your reservation has been successfully submitted!';
        header("Location: ticket.php?id=$reservation_id");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        throw $e;
    }
} catch (Exception $e) {
    $_SESSION['reservation_error'] = $e->getMessage();
    header('Location: reservation.php');
    exit();
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>
