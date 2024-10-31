<?php
session_start();
$fullName = $vehiclePlate = $phone = $reservationDate = '';

// Check if user data exists in the session
if (isset($_SESSION['user'])) {
    $fullName = isset($_SESSION['user']['full_name']) ? $_SESSION['user']['full_name'] : '';
    $vehiclePlate = isset($_SESSION['user']['vehicle_plate']) ? $_SESSION['user']['vehicle_plate'] : '';
    $phone = isset($_SESSION['user']['phone']) ? $_SESSION['user']['phone'] : '';
} else {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seasonal Parking - Parking System</title>
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

        /* Hero Section */
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

        /* Layout */
        .main-content {
            display: flex;
            flex-direction: row;
            gap: 2rem;
            padding-top: 2rem;
        }

        /* Map and Form */
        #map {
            height: 600px;
            width: 100%;
            border-radius: 10px;
        }

        .reservation-form {
            flex: 1;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .reservation-form:hover {
            transform: translateY(-5px);
        }

        .form-control-lg {
            height: calc(2.5rem + 2px);
            font-size: 1.1rem;
        }

        .price-display {
            display: none;
            font-size: 1.1rem;
            color: #007bff;
            margin-top: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            font-weight: bold;
            padding: 15px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .footer {
            background-color: #343a40;
            color: #fff;
            padding: 20px 0;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <?php include 'nav_bar.php'; ?>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Seasonal Parking Options</h1>
        <p>Discover our flexible seasonal parking plans tailored to your needs.</p>
    </div>

    <!-- Main Content -->
    <div class="container main-content">
        <!-- Map Section -->
        <div class="col-md-6">
            <h3 class="mb-4">Nairobi Parking Map</h3>
            <div id="map"></div>
        </div>

        <!-- Reservation Form Section -->
        <div class="reservation-form">
            <h3 class="text-center mb-4">Reserve Your Seasonal Parking</h3>
            <form action="seasonal_parking_logic.php" method="POST" id="parkingForm">
                <div class="form-group d-flex">
                    <label for="fullName" class="font-weight-bold mr-2">Full Name</label>
                    <input type="text" class="form-control form-control-lg" id="fullName" name="fullName"
                           value="<?= htmlspecialchars($fullName); ?>" placeholder="Enter your full name" required>
                </div>
                <div class="form-group d-flex">
                    <label for="phone" class="font-weight-bold mr-2">Phone Number</label>
                    <input type="text" class="form-control form-control-lg" id="phone" name="phone"
                           value="<?= htmlspecialchars($phone); ?>" placeholder="Enter your phone number" required>
                </div>
                <div class="form-group d-flex">
                    <label for="vehicle" class="font-weight-bold mr-2">Vehicle License Plate</label>
                    <input type="text" class="form-control form-control-lg" id="vehicle" name="vehicle"
                           value="<?= htmlspecialchars($vehiclePlate); ?>" placeholder="Enter your vehicle license plate" required>
                </div>
                <div class="form-group d-flex">
                    <label for="parkingArea" class="font-weight-bold mr-2">Select Parking Area</label>
                    <select class="form-control form-control-lg" id="parkingArea" name="parkingArea" required>
                        <option value="">Choose...</option>
                        <option value="area1" data-price="300">Nairobi Central</option>
                        <option value="area2" data-price="250">Westlands</option>
                        <option value="area3" data-price="200">Kilimani</option>
                        <option value="area4" data-price="150">Parklands</option>
                        <option value="area5" data-price="100">Karen</option>
                    </select>
                </div>
                <div class="form-group d-flex">
                    <label for="reservationDate" class="font-weight-bold mr-2">Reservation Date</label>
                    <input type="date" class="form-control form-control-lg" id="reservationDate" name="reservationDate" required>
                </div>
                <div class="form-group">
                    <label for="priceInput" class="font-weight-bold">Price (KES)</label>
                    <input type="text" class="form-control form-control-lg" id="priceInput" name="price" 
                           placeholder="Price will appear here" readonly>
                </div>
                <div class="form-group">
                    <label for="duration" class="font-weight-bold">Select Duration</label>
                    <select class="form-control form-control-lg" id="duration" name="duration" required>
                        <option value="">Choose...</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Reserve Now</button>
            </form>
        </div>
    </div>

    <!-- Modals for Messages -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Your reservation has been successfully made!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    There was an error processing your reservation. Please try again.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>AIzaSyDPQR8Xpxw0Igmvxn2L0tgqLk_IiK1I0AM
    </div>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p>&copy; 2024 Parking System. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Map Initialization
        function initMap() {
            var mapOptions = {
                center: { lat: -1.286389, lng: 36.817223 }, // Nairobi coordinates
                zoom: 12
            };
            var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        }

        document.getElementById('parkingArea').addEventListener('change', function () {
            var selectedOption = this.options[this.selectedIndex];
            var price = selectedOption.getAttribute('data-price') || 0;
            document.getElementById('priceInput').value = price;
        });

        // Call initMap after the page loads
        window.onload = initMap;
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPQR8Xpxw0Igmvxn2L0tgqLk_IiK1I0AM"></script>
</body>

</html>
