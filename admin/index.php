<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Display admin dashboard content
echo "<h1>Welcome to the Admin Dashboard, " . $_SESSION['admin_username'] . "</h1>";
echo '<a href="admin_logout.php">Logout</a>';
?>

<?php
include('db.php');

// Get totals from the database
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users"))['count'];
$totalDailyParking = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM daily_parking"))['count'];
$totalReservations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM reservations"))['count'];
$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(price) AS total FROM reservations"))['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons for the profile icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS for styling -->
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-x: hidden;
            font-family: 'Roboto', sans-serif;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
            color: #ffffff;
        }

        .container-fluid {
            margin-left: 250px;
            padding-right: 15px;
            padding-left: 15px;
            max-width: 1100px;
        }

        .main-content {
            padding-top: 80px;
        }

        .card-title {
            font-size: 1.3rem;
        }

        .card-text {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 20px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .chart-container {
            margin-top: 2rem;
        }

        .chart {
            width: 100%;
            height: 300px;
        }

        .row {
            margin-bottom: 2rem;
        }

        .card {
            margin-bottom: 1.5rem;
        }

        .navbar-text {
            font-weight: bold;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container-fluid {
                margin-left: 0;
            }

            .col-md-4 {
                flex: 1 0 100%;
                margin-bottom: 1rem;
            }

            .chart {
                height: 250px;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h4 class="text-center mb-4 text-info">Admin Dashboard</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-door"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="users.php"><i class="bi bi-person"></i> Manage Users</a></li>
            <li class="nav-item"><a class="nav-link" href="reservations.php"><i class="bi bi-calendar-check"></i> Manage Reservations</a></li>
            <li class="nav-item"><a class="nav-link" href="daily_parking.php"><i class="bi bi-car-front"></i> Manage Daily Parking</a></li>
            <li class="nav-item"><a class="nav-link" href="seasonal_parking.php"><i class="bi bi-calendar4-week"></i> Seasonal Parking</a></li>
        </ul>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" style="margin-left: 250px;">
        <div class="container">
            <span class="navbar-text me-auto">
                <strong><?php echo date('l, F j, Y'); ?></strong>
            </span>
            <div class="d-flex align-items-center">
                <span class="navbar-text me-2">Admin</span>
                <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid main-content">
        <h1 class="my-4">Admin Dashboard</h1>

        <!-- Stats Section -->
        <div class="row mb-4">
            <!-- Total Users -->
            <div class="col-md-4 col-sm-12">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo $totalUsers; ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Reservations -->
            <div class="col-md-4 col-sm-12">
                <div class="card bg-success text-white shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Reservations</h5>
                        <p class="card-text"><?php echo $totalReservations; ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="col-md-4 col-sm-12">
                <div class="card bg-warning text-white shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Revenue</h5>
                        <p class="card-text"><?php echo '$' . number_format($totalRevenue, 2); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <h2 class="my-4">Charts</h2>
        <div class="row chart-container">
            <div class="col-md-6 col-sm-12">
                <div class="chart">
                    <canvas id="reservationChart"></canvas>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="chart">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Manage Data Section -->
        <h2 class="my-4">Manage Data</h2>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <a href="users.php" class="btn btn-primary w-100">Manage Users</a>
            </div>
            <div class="col-md-4 col-sm-12">
                <a href="reservations.php" class="btn btn-primary w-100">Manage Reservations</a>
            </div>
            <div class="col-md-4 col-sm-12">
                <a href="daily_parking.php" class="btn btn-primary w-100">Manage Daily Parking</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var reservationData = [<?php echo $totalDailyParking; ?>, <?php echo $totalReservations; ?>];
        var revenueData = [<?php echo $totalRevenue; ?>];

        var ctx1 = document.getElementById('reservationChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Daily Parking', 'Reservations'],
                datasets: [{
                    label: 'Number of Reservations',
                    data: reservationData,
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
                    borderWidth: 1
                }]
            }
        });

        var ctx2 = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Revenue'],
                datasets: [{
                    label: 'Total Revenue',
                    data: revenueData,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            }
        });
    </script>
</body>

</html>
