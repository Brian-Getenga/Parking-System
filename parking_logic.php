<?php
session_start();

// Include the database connection
require 'config.php';

$message = '';

// Check if user is logged in and user ID is set
if (!isset($_SESSION['user']['user_id'])) {
    $message = 'User not logged in. Please log in to make a reservation.';
} else {
    $userId = $_SESSION['user']['user_id']; // Retrieve the user ID from the session

    try {
        // Check if form data is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $carLicense = trim($_POST['carLicense']);
            $parkingArea = trim($_POST['parkingArea']);
            $price = trim($_POST['price']);
            $paymentMethod = trim($_POST['paymentMethod']);

            // Validate inputs
            if (empty($carLicense) || empty($parkingArea) || empty($paymentMethod)) {
                throw new Exception('All fields are required.');
            }

            // Validate and sanitize price to ensure it's numeric and properly formatted
            if (!is_numeric($price)) {
                throw new Exception('Invalid price format. Please enter a valid number.');
            }

            $price = number_format((float)$price, 2, '.', ''); // Format as decimal

            // Prepare SQL insert statement
            $stmt = $pdo->prepare("INSERT INTO daily_parking (user_id, car_license, parking_area, price, payment_method) VALUES (:user_id, :car_license, :parking_area, :price, :payment_method)");

            // Bind parameters
            $stmt->bindParam(':user_id', $userId); // Bind the user ID
            $stmt->bindParam(':car_license', $carLicense);
            $stmt->bindParam(':parking_area', $parkingArea);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':payment_method', $paymentMethod);

            // Execute the statement
            if ($stmt->execute()) {
                $message = 'Parking reservation successful!';
            } else {
                $message = 'Error reserving parking. Please try again.';
            }
        }
    } catch (PDOException $e) {
        $message = 'Database error: ' . $e->getMessage();
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
        }
        .modal-header, .modal-footer {
            border: none;
        }
    </style>
</head>
<body>
    <?php include 'nav_bar.php'; ?>

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
                    <?php echo htmlspecialchars($message); ?>
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
        $(document).ready(function() {
            <?php if (!empty($message)): ?>
                $('#messageModal').modal('show');
            <?php endif; ?>
        });
    </script>
</body>
</html>
