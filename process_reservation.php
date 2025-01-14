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
    $delivery_address = htmlspecialchars(trim($_POST['delivery_address']));
    $event_type = htmlspecialchars(trim($_POST['event_type']));
    $package_id = intval($_POST['package_id']);
    $selected_dishes = htmlspecialchars(trim($_POST['selected_dishes']));
    $package_price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $payment_method = htmlspecialchars(trim($_POST['payment_method']));

    // Validate required fields
    if (!$first_name || !$last_name || !$contact || !$email || !$event_date || !$delivery_time || !$delivery_address || !$event_type || !$package_id || !$package_price || !$payment_method) {
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
    $gcashReceiptPath = null;
    if (isset($_FILES['gcash_receipt']) && $_FILES['gcash_receipt']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/receipts/';
        $fileTmpPath = $_FILES['gcash_receipt']['tmp_name'];
        $fileName = $_FILES['gcash_receipt']['name'];
        $fileSize = $_FILES['gcash_receipt']['size'];
        $fileType = $_FILES['gcash_receipt']['type'];
        $fileNameCmps = explode('.', $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Generate a new file name with auto-increment number
        $counterFile = 'uploads/receipts/counter.txt';
        if (!file_exists($counterFile)) {
            file_put_contents($counterFile, '0');
        }
        $counter = (int) file_get_contents($counterFile);
        $counter++;
        file_put_contents($counterFile, (string) $counter);

        $newFileName = 'GcashReceipt' . $counter . '.' . $fileExtension;

        // Check if the uploads/receipts directory exists, if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the file to the uploads/receipts directory with the new name
        $dest_path = $uploadDir . $newFileName;
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // File is successfully uploaded
            $gcashReceiptPath = $dest_path;
        } else {
            // Handle the error
            echo 'There was an error moving the uploaded file.';
            exit();
        }
    } else {
        echo 'No Gcash receipt was uploaded.';
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into reservations table
        $status = 'pending';
        $sql = "INSERT INTO reservations (
            customer_id, first_name, middle_name, last_name, contact, email,
            event_date, delivery_time, delivery_address, event_type,
            package_id, selected_dishes, gcash_receipt_path, status, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        $stmt->bind_param('isssssssssiiss', $customer_id, $first_name, $middle_name, $last_name, $contact, $email, $event_date, $delivery_time, $delivery_address, $event_type, $package_id, $selected_dishes, $gcashReceiptPath, $status);

        if (!$stmt->execute()) {
            throw new Exception('Failed to save reservation: ' . $stmt->error);
        }

        // Commit transaction
        $conn->commit();

        $_SESSION['reservation_success'] = 'Your reservation has been successfully submitted!';
        header('Location: ticket.php?id=' . $stmt->insert_id);
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
