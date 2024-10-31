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
    <title>Your Reservations</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .hero-section {
            background: url('pexels-pixabay-63294.jpg') no-repeat center center / cover;
            height: 50vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .hero-section h2, .hero-section p {
            z-index: 2;
        }
        .reservation-section {
            margin-top: 1.5rem;
        }
        .card {
            margin: 1rem 0;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .ticket-actions {
            margin-top: 1rem;
        }
        .filter-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .form-control, .btn {
            margin: 0.5rem 0;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php include 'nav_bar.php'; ?>

<!-- Hero Section -->
<div class="hero-section">
    <h2>Your Reservations</h2>
    <p>Manage and track your daily, seasonal, and other parking reservations here.</p>
</div>

<div class="container">
    <!-- Filter Section -->
    <div class="filter-section">
        <input type="text" id="searchInput" onkeyup="filterReservations()" class="form-control" placeholder="Search by license plate, area...">
        <select id="filterType" class="form-control" onchange="filterReservations()">
            <option value="all">All Reservations</option>
            <option value="daily">Daily Parking</option>
            <option value="seasonal">Seasonal Parking</option>
            <option value="other">Other Reservations</option>
        </select>
    </div>

    <!-- Reservation Sections -->
    <div id="reservationContainer">
        <!-- Daily Parking Section -->
        <div class="reservation-section" id="daily">
            <h3 class="text-primary">Daily Parking</h3>
            <?php
            $stmt = $pdo->prepare("SELECT * FROM daily_parking WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $dailyParkings = $stmt->fetchAll();

            if ($dailyParkings) {
                foreach ($dailyParkings as $parking) {
                    ?>
                    <div class="card reservation-card" data-type="daily">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($parking['car_license']) ?></h5>
                            <p class="card-text">
                                <strong>Parking Area:</strong> <?= htmlspecialchars($parking['parking_area']) ?><br>
                                <strong>Price:</strong> <?= htmlspecialchars($parking['price']) ?><br>
                                <strong>Payment Method:</strong> <?= htmlspecialchars($parking['payment_method']) ?><br>
                                <strong>Date:</strong> <?= htmlspecialchars($parking['reservation_date']) ?>
                            </p>
                            <div class="btn-group ticket-actions">
                                <a href="generate_receipt.php?id=<?= $parking['id'] ?>&type=daily" class="btn btn-success btn-sm">
                                    <i class="fas fa-receipt"></i> Download Receipt
                                </a>
                                <button onclick="printTicket('<?= $parking['id'] ?>')" class="btn btn-primary btn-sm btn-download">
                                    <i class="fas fa-print"></i> Print Ticket
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No daily parking reservations found.</p>';
            }
            ?>
        </div>

        <!-- Seasonal Parking Section -->
        <div class="reservation-section" id="seasonal">
            <h3 class="text-primary">Seasonal Parking</h3>
            <?php
            $stmt = $pdo->prepare("SELECT * FROM parking_reservation WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $seasonalParkings = $stmt->fetchAll();

            if ($seasonalParkings) {
                foreach ($seasonalParkings as $parking) {
                    ?>
                    <div class="card reservation-card" data-type="seasonal">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($parking['vehicle_license']) ?></h5>
                            <p class="card-text">
                                <strong>Parking Area:</strong> <?= htmlspecialchars($parking['parking_area']) ?><br>
                                <strong>Duration:</strong> <?= htmlspecialchars($parking['duration']) ?><br>
                                <strong>Price:</strong> <?= htmlspecialchars($parking['price']) ?><br>
                                <strong>Date:</strong> <?= htmlspecialchars($parking['reservation_date']) ?>
                            </p>
                            <div class="btn-group ticket-actions">
                                <a href="generate_receipt.php?id=<?= $parking['id'] ?>&type=seasonal" class="btn btn-success btn-sm">
                                    <i class="fas fa-receipt"></i> Download Receipt
                                </a>
                                <button onclick="printTicket('<?= $parking['id'] ?>')" class="btn btn-primary btn-sm btn-download">
                                    <i class="fas fa-print"></i> Print Ticket
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No seasonal parking reservations found.</p>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Bootstrap and JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function filterReservations() {
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();
        const filterType = document.getElementById('filterType').value;
        const reservations = document.querySelectorAll('.reservation-card');

        reservations.forEach(card => {
            const type = card.getAttribute('data-type');
            const textContent = card.textContent.toLowerCase();
            if ((filterType === 'all' || type === filterType) && textContent.includes(searchQuery)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function printTicket(reservationId) {
        window.open('generate_receipt.php?id=' + reservationId + '&print=true', '_blank');
    }
</script>
</body>
</html>
