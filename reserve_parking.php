<?php
session_start(); // Start the session to access user data

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit(); // Stop further execution after the redirect
}

// Get user details from the session
$userFullName = $_SESSION['user']['full_name'] ?? '';
$userEmail = $_SESSION['user']['email'] ?? '';
$userVehicleLicense = $_SESSION['user']['vehicle_plate'] ?? '';
$reservationDate = date('Y-m-d'); // Current date in Y-m-d format

// Initialize message variables
$message = $_SESSION['success'] ?? '';
$errorMessage = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']); // Clear messages after use

// Prices for different parking areas
$parkingPrices = [
    "Westlands" => 200,
    "Nairobi CBD" => 300,
    "Lang'ata" => 150,
    "Karen" => 250,
    "Juja" => 100,
    "Eastleigh" => 180,
    "Mombasa Road" => 220,
    "Kilimani" => 240,
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Parking - Parking System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            color: #333;
        }

        .hero {
            background: url('pexels-pixabay-63294.jpg') no-repeat center center / cover;
            height: 60vh;
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            transition: 2s ease;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .hero h1 {
            font-size: 3.5rem;
            z-index: 2;
        }

        .hero p {
            font-size: 1.2rem;
            z-index: 2;
        }

        .reservation-form {
            background-color: #f8f9fa;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .footer {
            background-color: #343a40;
            color: #fff;
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <?php include 'nav_bar.php'; ?>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Reserve Your Parking Spot</h1>
        <p>Fill out the form below to reserve your parking spot.</p>
    </div>

    <!-- Message Alerts -->
    <?php if ($message || $errorMessage): ?>
        <div class="container mt-3">
            <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="messageModalLabel">Message</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <?php echo htmlspecialchars($message ?: $errorMessage); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#messageModal').modal('show');
            });
        </script>
    <?php endif; ?>

    <!-- Reservation Form Section -->
    <div class="container reservation-form">
        <h3 class="text-center mb-4">Parking Reservation Form</h3>
        <form action="reserve_logic.php" method="POST">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="full_name">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($userFullName); ?>" readonly required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" readonly required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="vehicle_license">Vehicle License Plate</label>
                    <input type="text" class="form-control" id="vehicle_license" name="vehicle_license" value="<?php echo htmlspecialchars($userVehicleLicense); ?>" readonly required>
                </div>
                <div class="form-group col-md-6">
                    <label for="reservation_date">Reservation Date</label>
                    <input type="date" class="form-control" id="reservation_date" name="reservation_date" value="<?php echo htmlspecialchars($reservationDate); ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="parking_area">Select Parking Area</label>
                    <select class="form-control" id="parking_area" name="parking_area" required onchange="updatePrice()">
                        <option value="">Choose...</option>
                        <?php foreach ($parkingPrices as $area => $price): ?>
                            <option value="<?php echo htmlspecialchars($area); ?>"><?php echo htmlspecialchars($area); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="price">Price (KSh)</label>
                    <input type="number" class="form-control" id="price" name="price" placeholder="Price will appear here" required readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="duration">Duration</label>
                    <select class="form-control" id="duration" name="duration" required>
                        <option value="">Choose...</option>
                        <option value="1_hour">1 Hour</option>
                        <option value="2_hours">2 Hours</option>
                        <option value="daily">Daily</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="payment_method">Payment Method</label>
                    <select class="form-control" id="payment_method" name="payment_method" required onchange="toggleMpesaInput()">
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="mpesa">MPesa</option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="mpesa_input" style="display:none;">
                <label for="mpesa_phone">MPesa Phone Number</label>
                <input type="text" class="form-control" id="mpesa_phone" name="mpesa_phone" placeholder="Enter your phone number">
            </div>
            <div class="form-group">
                <label for="notes">Additional Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional requests or notes"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Reserve Now</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <span>&copy; 2024 Parking System. All rights reserved.</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
            $(document).ready(function() {
                $('#messageModal').modal('show');
            });
    
        function updatePrice() {
            const parkingArea = document.getElementById('parking_area').value;
            const prices = <?php echo json_encode($parkingPrices); ?>;
            const priceInput = document.getElementById('price');

            if (parkingArea) {
                priceInput.value = prices[parkingArea];
            } else {
                priceInput.value = '';
            }
        }

        function toggleMpesaInput() {
            const paymentMethod = document.getElementById('payment_method').value;
            const mpesaInput = document.getElementById('mpesa_input');

            if (paymentMethod === 'mpesa') {
                mpesaInput.style.display = 'block';
            } else {
                mpesaInput.style.display = 'none';
            }
        }
    </script>
</body>
</html>
