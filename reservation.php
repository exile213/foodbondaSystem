<?php
require_once 'session.php';
require_once 'db_conn.php';
$customer_id = $_SESSION['customer_id'] ?? null;
if ($customer_id) {
    $stmt = $conn->prepare('SELECT first_name, middle_name, last_name, email FROM customers WHERE customer_id = ?');
    $stmt->bind_param('i', $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
}
$selected_package = isset($_GET['package']) ? $_GET['package'] : '';

// Fetch packages from the database
$sql = 'SELECT package_name, price, included_dishes, additional_dishes_limit FROM packages';
$result = $conn->query($sql);
$packages = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['price'] = (float) $row['price']; // Ensure price is a float
        $packages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Reservation</title>
    <link rel="icon" type="image/x-icon" href="logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="reservation.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>

        <form class="reservation-form" action="<?php echo isset($update) ? 'process_update_reservation.php' : 'process_reservation.php'; ?>" method="POST" enctype="multipart/form-data"
            id="reservationForm">
            <h2><?php echo isset($update) ? 'Update Your Reservation' : 'Make Your Reservation'; ?></h2>

            <!-- Personal Information Section -->
            <div class="form-section">
                <h3><i class="fas fa-user"></i> Personal Information</h3>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required
                            maxlength="20" value="<?php echo htmlspecialchars($customer['first_name'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name" maxlength="20"
                            value="<?php echo htmlspecialchars($customer['middle_name'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required
                            maxlength="20" value="<?php echo htmlspecialchars($customer['last_name'] ?? ''); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="contact" class="form-label">Contact Number</label>
                        <input type="tel" class="form-control" id="contact" name="contact" required maxlength="11"
                            pattern="[0-9]{11}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required
                            value="<?php echo htmlspecialchars($customer['email'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <!-- Event Details Section -->
            <div class="form-section">
                <h3><i class="fas fa-calendar-alt"></i> Event Details</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="event_date" class="form-label">Event Date</label>
                        <input type="date" class="form-control" id="event_date" name="event_date" required
                            min="<?php echo date('Y-m-d', strtotime('+3 days')); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="delivery_time" class="form-label">Delivery Time</label>
                        <input type="time" class="form-control" id="delivery_time" name="delivery_time" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Delivery Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="event" class="form-label">Event Type</label>
                    <select class="form-select" id="event" name="event" required>
                        <option value="">Select event type</option>
                        <option value="wedding">Wedding</option>
                        <option value="birthday">Birthday</option>
                        <option value="christening">Christening</option>
                        <option value="thanksgiving">Thanksgiving</option>
                        <option value="fiesta">Fiesta</option>
                    </select>
                </div>
            </div>

            <?php if (!isset($update)): ?>
            <!-- Package Selection Section -->
            <div class="form-section">
                <h3><i class="fas fa-box"></i> Package Selection</h3>
                <div class="mb-3">
                    <label for="package" class="form-label">Choose Package</label>
                    <select class="form-select" id="package" name="package" required onchange="updateDishLimit()">
                        <option value="">Select a package</option>
                        <?php foreach ($packages as $package): ?>
                        <option value="<?php echo htmlspecialchars($package['package_name']); ?>" <?php echo $selected_package === $package['package_name'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($package['package_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="selectedDishes" class="form-label">Included Dishes</label>
                    <textarea class="form-control" id="selectedDishes" name="selectedDishes" readonly></textarea>
                </div>
                <div id="additionalDishes" style="display: none;" class="mb-3">
                    <label class="form-label">Additional Dishes</label>
                    <p id="dishLimit" class="text-muted"></p>
                    <div class="row g-3">
                        <?php
                        $availableDishes = ['Lumpia shanghai-6opcs', 'Lumpia veggies-6opc', 'Fish fillet -2kls', 'Fish sweet and sour', 'Chopsuey', 'Whole chicken estofado', 'Chicken sisig', 'Chicken afritada', 'Buffalo wings', 'Chicken pastel', 'Chicken Curry', 'Pork menudo', 'Pork siomai', 'Pancit', 'Bihon', 'Spaghetti', 'Carbonara', 'Valenciana'];
                        
                        foreach ($availableDishes as $dish) {
                            echo '<div class="col-md-4">
                                                                                                                                                                                                                                                        <div class="form-check">
                                                                                                                                                                                                                                                            <input class="form-check-input" type="checkbox" name="additionalDishes[]" value="' .
                                htmlspecialchars($dish) .
                                '" id="' .
                                htmlspecialchars($dish) .
                                '">
                                                                                                                                                                                                                                                            <label class="form-check-label" for="' .
                                htmlspecialchars($dish) .
                                '">' .
                                htmlspecialchars($dish) .
                                '</label>
                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                    </div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="originalPrice" class="form-label">Original Price</label>
                    <input type="text" class="form-control" id="originalPrice" readonly>
                </div>
                <div class="mb-3">
                    <label for="priceDisplay" class="form-label">Downpayment Package Price</label>
                    <input type="text" class="form-control" id="priceDisplay" readonly>
                    <input type="hidden" id="price" name="price">
                </div>
            </div>

            <!-- Payment Method Section -->
            <div class="form-section">
                <h3><i class="fas fa-money-bill"></i> Payment Details</h3>
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <p><strong>Note:</strong> We are only using GCash as our mode of payment in reservations.</p>
                    <select class="form-select" id="payment_method" name="payment_method" required
                        onchange="toggleGcashUpload()">
                        <option value="Downpayment 50%">Downpayment 50% (GCash)</option>
                    </select>
                </div>
                <div id="gcashSection" class="gcash-section" style="display: none;">
                    <h4>GCash Payment Details</h4>
                    <p><strong>GCash Number:</strong> 09300712088</p>
                    <p><strong>Account Name:</strong> JO*N PA*L B.</p>
                    <img src="qr.jpg" alt="GCash QR Code" class="img-fluid">
                    <div class="mb-3 mt-3">
                        <label for="gcash_receipt" class="form-label">Upload GCash Receipt</label>
                        <input type="file" class="form-control" id="gcash_receipt" name="gcash_receipt"
                            accept=".jpg,.jpeg,.png,.gif">
                        <p id="file-name" class="form-text"></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary"><?php echo isset($update) ? 'Update Reservation' : 'Submit Reservation'; ?></button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const packageSelect = document.getElementById('package');
            const origPrice = document.getElementById('originalPrice');
            const selectedDishesTextarea = document.getElementById('selectedDishes');
            const priceInput = document.getElementById('price');
            const priceDisplay = document.getElementById('priceDisplay');
            const paymentMethodSelect = document.getElementById('payment_method');
            const additionalDishesElement = document.getElementById('additionalDishes');
            const dishLimitElement = document.getElementById('dishLimit');

            const packages = <?php echo json_encode($packages); ?>;

            function updatePackageInfo() {
                const selectedPackage = packages.find(pkg => pkg.package_name === packageSelect.value);
                if (!selectedPackage) {
                    selectedDishesTextarea.value = '';
                    priceDisplay.value = '';
                    priceInput.value = '';
                    origPrice.value = '';
                    additionalDishesElement.style.display = 'none';
                    dishLimitElement.textContent = '';
                    return;
                }

                // Update the dishes display
                selectedDishesTextarea.value = selectedPackage.included_dishes;

                // Format and display original price
                origPrice.value = '₱' + parseFloat(selectedPackage.price).toFixed(2);

                // Calculate and display price
                let displayPrice = parseFloat(selectedPackage.price);
                if (paymentMethodSelect.value === 'Downpayment 50%') {
                    displayPrice = displayPrice / 2;
                }

                priceDisplay.value = '₱' + displayPrice.toFixed(2);
                priceInput.value = selectedPackage.price.toFixed(2);

                // Handle additional dish selections for packages with limits
                if (selectedPackage.additional_dishes_limit) {
                    additionalDishesElement.style.display = 'block';
                    dishLimitElement.textContent =
                        `Please select ${selectedPackage.additional_dishes_limit} additional dishes`;
                } else {
                    additionalDishesElement.style.display = 'none';
                    dishLimitElement.textContent = '';
                }
            }

            // Event listeners
            packageSelect.addEventListener('change', updatePackageInfo);
            paymentMethodSelect.addEventListener('change', updatePackageInfo);

            // Initialize form
            updatePackageInfo();

            // Additional dish selection validation
            const additionalDishCheckboxes = document.querySelectorAll('#additionalDishes input[type="checkbox"]');
            additionalDishCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const selectedPackage = packages.find(pkg => pkg.package_name === packageSelect
                        .value);
                    if (selectedPackage && selectedPackage.additional_dishes_limit) {
                        const checkedBoxes = document.querySelectorAll(
                            '#additionalDishes input[type="checkbox"]:checked');
                        if (checkedBoxes.length > selectedPackage.additional_dishes_limit) {
                            this.checked = false;
                            alert(
                                `You can only select up to ${selectedPackage.additional_dishes_limit} additional dishes for this package.`
                            );
                        }
                    }
                });
            });
        });

        function updateDishLimit() {
            const package = document.getElementById('package').value;
            const limitText = document.getElementById('dishLimit');
            limitText.textContent = 'Please select up to X dishes for this package';
        }

        function toggleGcashUpload() {
            const paymentMethod = document.getElementById('payment_method').value;
            const gcashSection = document.getElementById('gcashSection');
            const gcashReceipt = document.getElementById('gcash_receipt');

            if (paymentMethod === 'Downpayment 50%') {
                gcashSection.style.display = 'block';
                gcashReceipt.required = true;
            } else {
                gcashSection.style.display = 'none';
                gcashReceipt.required = false;
            }
        }

        function updateFileName() {
            const fileInput = document.getElementById('gcash_receipt');
            const fileName = document.getElementById('file-name');
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                fileName.textContent = `Selected file: ${file.name}`;
            }
        }

        // Add event listeners
        document.getElementById('gcash_receipt').addEventListener('change', updateFileName);

        // Initialize form state
        updateDishLimit();
        toggleGcashUpload();
    </script>
</body>

</html>
