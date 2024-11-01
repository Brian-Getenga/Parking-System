<?php
session_start(); // Start the session to access user data

// Check if user is logged in
if (!isset($_SESSION['user'])) { // Assuming 'user_id' is stored in session upon login
    header('Location: login.php'); // Redirect to login page
    exit(); // Stop further execution
}

// Include the database connection
require 'config.php'; // Ensure this file contains the PDO connection setup

$message = ''; // Variable to hold the message to be displayed

try {
    // Check if form data is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $carLicense = $_POST['carLicense'];
        $parkingArea = $_POST['parkingArea'];
        $price = $_POST['price']; // Price sent from the form
        $paymentMethod = $_POST['paymentMethod'];

        // Prepare SQL insert statement
        $stmt = $pdo->prepare("INSERT INTO daily_parking (car_license, parking_area, price, payment_method) VALUES (:car_license, :parking_area, :price, :payment_method)");

        // Bind parameters
        $stmt->bindParam(':car_license', $carLicense);
        $stmt->bindParam(':parking_area', $parkingArea);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':payment_method', $paymentMethod);

        // Execute the statement
        if ($stmt->execute()) {
            $message = 'Parking reservation successful!'; // Success message
        } else {
            $message = 'Error reserving parking. Please try again.'; // Error message
        }
    }
} catch (PDOException $e) {
    $message = 'Connection failed: ' . $e->getMessage();
}

// HTML starts here
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Information - Parking System</title>
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
            background-color: #f8f9fa;
        }

        /* Hide the price label and input by default */
        #priceLabel,
        #price {
            display: none;
        }

        /* Other styles */
        .hero {
            background: url('pexels-pixabay-63294.jpg') no-repeat center center / cover;
            height: 60vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            transition: 2s ease;
            gap: 2rem;
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
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <?php include 'nav_bar.php'; ?>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Parking Information</h1>
        <p>Your guide to parking services <br> and availability.</p>
    </div>

    <!-- Main Content Section -->
    <div class="container mt-5">
        <div class="row">
            <!-- Features Section -->
            <div class="col-md-4 features">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card feature-card mb-4">
                            <div class="card-body text-center">
                                <i class="fas fa-car fa-3x mb-3"></i>
                                <h5 class="card-title">Convenience</h5>
                                <p class="card-text">Easily reserve a parking spot online, ensuring you have a place waiting for you.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card feature-card mb-4">
                            <div class="card-body text-center">
                                <i class="fas fa-lock fa-3x mb-3"></i>
                                <h5 class="card-title">Secure Payments</h5>
                                <p class="card-text">We offer secure online payment options for your peace of mind.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Reservation Form -->
            <div class="col-md-8">
                <h2 class="text-center mt-5 mb-4">Daily Parking Reservation</h2>
                <div class="reservation-form">
                    <form action="" method="POST"> <!-- Form action points to the same page -->
                        <div class="form-group">
                            <label for="carLicense">Car License Plate</label>
                            <input type="text" class="form-control" id="carLicense" name="carLicense" placeholder="Enter your car license plate" required>
                        </div>
                        <div class="form-group">
                            <label for="parkingArea">Select Parking Area</label>
                            <select class="form-control" id="parkingArea" name="parkingArea" required>
                                <option value="" disabled selected>Select parking area</option>
                                <option value="CBD">CBD</option>
                                <option value="Westlands">Westlands</option>
                                <option value="Karen">Karen</option>
                                <option value="Kasarani">Kasarani</option>
                                <option value="Upper Hill">Upper Hill</option>
                            </select>
                        </div>
                        <!-- Price field, hidden by default -->
                        <div class="form-group">
                            <label for="price" id="priceLabel">Parking Price</label>
                            <input type="text" class="form-control" id="price" name="price" readonly>
                        </div>
                        <div class="form-group">
                            <label for="paymentMethod">Payment Method</label>
                            <select class="form-control" id="paymentMethod" name="paymentMethod" required>
                                <option value="" disabled selected>Select payment method</option>
                                <option value="creditCard">Credit Card</option>
                                <option value="debitCard">Debit Card</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Reserve Parking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for displaying messages -->
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($message)): ?>
                        <?php echo $message; ?> <!-- Display message here -->
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="window.location.href='index.php'">Go to Homepage</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0">© 2024 Parking System. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Prices for each parking area
        const parkingPrices = {
            "CBD": "200 KES",
            "Westlands": "150 KES",
            "Karen": "300 KES",
            "Kasarani": "100 KES",
            "Upper Hill": "250 KES"
        };

        // Update the price field based on selected area
        document.getElementById("parkingArea").addEventListener("change", function () {
            const area = this.value;
            const priceInput = document.getElementById("price");
            const priceLabel = document.getElementById("priceLabel");
            if (area) {
                priceInput.value = parkingPrices[area]; // Set price based on selected area
                priceInput.style.display = "block"; // Show price input
                priceLabel.style.display = "block"; // Show price label
            } else {
                priceInput.value = "";
                priceInput.style.display = "none"; // Hide price input
                priceLabel.style.display = "none"; // Hide price label
            }
        });

        // Show the modal with message if exists
        $(document).ready(function () {
            <?php if (!empty($message)): ?>
                $('#messageModal').modal('show'); // Show the modal if there's a message
            <?php endif; ?>
        });
    </script>
</body>

</html>
