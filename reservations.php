<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Reservations</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #e9ecef; /* Light background for better contrast */
            color: #495057;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('pexels-pixabay-63294.jpg') no-repeat center center / cover;
            height: 60vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
            border-radius: 0 0 15px 15px; /* Rounded bottom corners */
        }
        .hero-section h2 {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        .hero-section p {
            font-size: 1.25rem;
        }
        .card {
            border: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
            background-color: #ffffff; /* White background for cards */
        }
        .card-header {
            color: black;
            font-weight: 300;
            font-size: 1.15rem;
            padding: 10px;
        }
        .card-header span{
            color: white;
        }
        .card-body {
            padding: 10px;
        }
        .card-body p {
            color: #343a40; /* Darker text color for better readability */
        }
        .btn-primary, .btn-danger {
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .search-container {
            margin: 20px 0;
        }
        @media (max-width: 768px) {
            .hero-section h2 {
                font-size: 2.5rem;
            }
            .hero-section p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php include "nav_bar.php"?>

<!-- Hero Section -->
<div class="hero-section">
    <h2>Your Reservations</h2>
    <p>Manage your parking reservations with ease and confidence.</p>
</div>

<div class="container">
    <!-- Search Bar -->
    <div class="search-container">
        <div class="input-group">
            <input type="text" id="search" class="form-control" placeholder="Search reservations..." aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-primary" id="searchBtn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Reservation Sections -->
    <div id="reservationContainer">
        <!-- Daily Parking Section -->
        <div class="reservation-section" id="daily">
            <h3 class="text-primary font-weight-bold">Daily Parking</h3>
            <div class="row">
                <?php
                // Debugging: Check if user_id is set correctly
                echo "<!-- User ID: $user_id -->";
                
                $stmt = $pdo->prepare("SELECT * FROM daily_parking WHERE user_id = :user_id");
                $stmt->execute(['user_id' => $user_id]);
                $dailyParkings = $stmt->fetchAll();

                if ($dailyParkings) {
                    foreach ($dailyParkings as $parking) {
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="card reservation-card">
                                <div class="card-header bg-warning">
                                    Car License: <span><?= htmlspecialchars($parking['car_license']) ?></span>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <strong>Parking Area:</strong> <?= htmlspecialchars($parking['parking_area']) ?><br>
                                        <strong>Price:</strong> Ksh <?= htmlspecialchars(number_format($parking['price'], 2)) ?><br>
                                        <strong>Payment Method:</strong> <?= htmlspecialchars($parking['payment_method']) ?><br>
                                        <strong>Reservation Date:</strong> <?= htmlspecialchars($parking['reservation_date']) ?><br>
                                        <strong>Duration:</strong> <?= htmlspecialchars($parking['duration']) ?> hours
                                    </p>
                                    <div class="btn-group">
                                        <button onclick="printTicket('<?= $parking['id'] ?>', 'daily')" class="btn btn-primary btn-sm">
                                            <i class="fas fa-print"></i> Print Ticket
                                        </button>
                                        <button onclick="cancelReservation('<?= $parking['id'] ?>', 'daily')" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="col-12">No daily parking reservations found.</p>';
                }
                ?>
            </div>
        </div>

        <!-- Seasonal Parking Section -->
        <div class="reservation-section" id="seasonal">
            <h3 class="text-primary font-weight-bold">Seasonal Parking</h3>
            <div class="row">
                <?php
                $stmt = $pdo->prepare("SELECT * FROM parking_reservation WHERE user_id = :user_id");
                $stmt->execute(['user_id' => $user_id]);
                $seasonalParkings = $stmt->fetchAll();

                if ($seasonalParkings) {
                    foreach ($seasonalParkings as $parking) {
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="card reservation-card">
                                <div class="card-header bg-info">
                                    Vehicle License: <span><?= htmlspecialchars($parking['vehicle_license']) ?></span>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <strong>Parking Area:</strong> <?= htmlspecialchars($parking['parking_area']) ?><br>
                                        <strong>Duration:</strong> <?= htmlspecialchars($parking['duration']) ?> days<br>
                                        <strong>Price:</strong> Ksh <?= htmlspecialchars(number_format($parking['price'], 2)) ?><br>
                                        <strong>Payment Method:</strong> <?= htmlspecialchars($parking['payment_method']) ?><br>
                                        <strong>Reservation Date:</strong> <?= htmlspecialchars($parking['reservation_date']) ?><br>
                                    </p>
                                    <div class="btn-group">
                                        <button onclick="printTicket('<?= $parking['id'] ?>', 'seasonal')" class="btn btn-primary btn-sm">
                                            <i class="fas fa-print"></i> Print Ticket
                                        </button>
                                        <button onclick="cancelReservation('<?= $parking['id'] ?>', 'seasonal')" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="col-12">No seasonal parking reservations found.</p>';
                }
                ?>
            </div>
        </div>

        <!-- Reservation Section -->
        <div class="reservation-section" id="reservations">
            <h3 class="text-primary font-weight-bold">All Reservations</h3>
            <div class="row">
                <?php
                $stmt = $pdo->prepare("SELECT * FROM reservations WHERE user_id = :user_id");
                $stmt->execute(['user_id' => $user_id]);
                $allReservations = $stmt->fetchAll();

                if ($allReservations) {
                    foreach ($allReservations as $reservation) {
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="card reservation-card">
                                <div class="card-header bg-success">
                                <strong>Vehicle Plate:</strong> <span><?= htmlspecialchars($reservation['vehicle_plate']) ?></span>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <strong>Duration:</strong> <?= htmlspecialchars($reservation['duration']) ?> days<br>
                                        <strong>Created At:</strong> <?= htmlspecialchars($reservation['created_at']) ?><br>
                                        <strong>Price:</strong> Ksh <?= htmlspecialchars(number_format($reservation['price'], 2)) ?>
                                    </p>
                                    <div class="btn-group">
                                        <button onclick="printTicket('<?= $reservation['reservation_id'] ?>', 'general')" class="btn btn-primary btn-sm">
                                            <i class="fas fa-print"></i> Print Receipt
                                        </button>
                                        <button onclick="cancelReservation('<?= $reservation['reservation_id'] ?>', 'general')" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="col-12">No reservations found.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function printTicket(id, type) {
        window.open('print_ticket.php?id=' + id + '&type=' + type, '_blank');
    }

    function cancelReservation(id, type) {
        if (confirm("Are you sure you want to cancel this reservation?")) {
            $.ajax({
                url: 'cancel_reservation.php',
                type: 'POST',
                data: { id: id, type: type },
                success: function(response) {
                    if (response.success) {
                        alert("Reservation cancelled successfully.");
                        location.reload();
                    } else {
                        alert("Error cancelling reservation.");
                    }
                }
            });
        }
    }

    // Search functionality
    $('#searchBtn').click(function() {
        var query = $('#search').val().toLowerCase();
        $('.reservation-card').each(function() {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(query) > -1);
        });
    });
</script>

</body>
</html>
