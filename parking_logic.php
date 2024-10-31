<?php
session_start(); // Start the session

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

// Include HTML to display modal
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Information - Parking System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'nav_bar.php' ?>

    <!-- Your content here -->
    
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
                    <?php echo $message; ?> <!-- Display message here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="window.location.href='index.php'">Go to Homepage</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Show the modal after the page loads if there's a message
        $(document).ready(function() {
            <?php if (!empty($message)): ?>
                $('#messageModal').modal('show');
            <?php endif; ?>
        });
    </script>
</body>
</html>
