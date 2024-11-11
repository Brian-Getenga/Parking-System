<?php
// Assuming you have already set up your database connection and session handling
include 'config.php'; // for DB connection

// Fetch totals for Dashboard in one query
$queryTotals = "
    SELECT 
        (SELECT COUNT(*) FROM reservations) AS totalReservations,
        (SELECT COUNT(*) FROM daily_parking) AS totalDailyParking,
        (SELECT COUNT(*) FROM parking_reservation) AS totalSeasonalParking
";

$totals = $pdo->query($queryTotals)->fetch(PDO::FETCH_ASSOC);

// Fetch users, reservations, parking, and seasonal data
$usersQuery = "SELECT * FROM users";
$reservationsQuery = "SELECT * FROM reservations";
$dailyParkingQuery = "SELECT * FROM daily_parking";
$seasonalParkingQuery = "SELECT * FROM parking_reservation";

$users = $pdo->query($usersQuery)->fetchAll(PDO::FETCH_ASSOC);
$reservations = $pdo->query($reservationsQuery)->fetchAll(PDO::FETCH_ASSOC);
$dailyParking = $pdo->query($dailyParkingQuery)->fetchAll(PDO::FETCH_ASSOC);
$seasonalParking = $pdo->query($seasonalParkingQuery)->fetchAll(PDO::FETCH_ASSOC);

// CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add reservation
    if (isset($_POST['add_reservation'])) {
        $full_name = $_POST['full_name'];
        $vehicle_plate = $_POST['vehicle_plate'];
        $duration = $_POST['duration'];
        $price = $_POST['price'];
        $query = "INSERT INTO reservations (full_name, vehicle_plate, duration, price, reservation_date) 
                  VALUES (?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$full_name, $vehicle_plate, $duration, $price]);
    }
    // Edit reservation
    if (isset($_POST['edit_reservation'])) {
        $reservation_id = $_POST['reservation_id'];
        $full_name = $_POST['full_name'];
        $vehicle_plate = $_POST['vehicle_plate'];
        $duration = $_POST['duration'];
        $price = $_POST['price'];
        $query = "UPDATE reservations SET full_name = ?, vehicle_plate = ?, duration = ?, price = ? WHERE reservation_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$full_name, $vehicle_plate, $duration, $price, $reservation_id]);
    }
    // Delete reservation
    if (isset($_GET['delete_reservation'])) {
        $reservation_id = $_GET['delete_reservation'];
        $query = "DELETE FROM reservations WHERE reservation_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$reservation_id]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 p-0">
                <div class="bg-dark text-white" style="height: 100vh;">
                    <div class="sidebar-header">
                        <h3 class="text-center py-3">Parking Admin</h3>
                    </div>
                    <ul class="nav flex-column p-3">
                        <li class="nav-item"><a class="nav-link text-white" href="#dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="#reservations">Reservations</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="#daily_parking">Daily Parking</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="#seasonal_parking">Seasonal Parking</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="#users">Users</a></li>
                    </ul>
                </div>
            </div>
            <!-- Main Content -->
            <div class="col-md-9 p-4">
                <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
                    <a class="navbar-brand" href="#">Parking System</a>
                    <span class="ml-auto"><?= date("l, F j, Y"); ?></span>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> Admin
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Profile</a>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </div>
                </nav>

                <!-- Dashboard -->
                <div id="dashboard">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Reservations</h5>
                                    <p class="card-text"><?= $totals['totalReservations']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Daily Parking</h5>
                                    <p class="card-text"><?= $totals['totalDailyParking']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Seasonal Parking</h5>
                                    <p class="card-text"><?= $totals['totalSeasonalParking']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reservations -->
                <div id="reservations">
                    <h3>Reservations</h3>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addReservationModal">Add Reservation</button>
                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Vehicle Plate</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($reservations as $reservation): ?>
                                <tr>
                                    <td><?= $reservation['reservation_id']; ?></td>
                                    <td><?= $reservation['full_name']; ?></td>
                                    <td><?= $reservation['vehicle_plate']; ?></td>
                                    <td><?= $reservation['duration']; ?></td>
                                    <td><?= $reservation['price']; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editReservationModal" data-id="<?= $reservation['reservation_id']; ?>" data-name="<?= $reservation['full_name']; ?>" data-plate="<?= $reservation['vehicle_plate']; ?>" data-duration="<?= $reservation['duration']; ?>" data-price="<?= $reservation['price']; ?>">Edit</button>
                                        <a href="?delete_reservation=<?= $reservation['reservation_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add Reservation Modal -->
                <div class="modal fade" id="addReservationModal" tabindex="-1" role="dialog" aria-labelledby="addReservationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addReservationModalLabel">Add Reservation</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label for="full_name">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="vehicle_plate">Vehicle Plate</label>
                                        <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="duration">Duration (in hours)</label>
                                        <input type="number" class="form-control" id="duration" name="duration" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" class="form-control" id="price" name="price" required>
                                    </div>
                                    <button type="submit" name="add_reservation" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Reservation Modal -->
                <div class="modal fade" id="editReservationModal" tabindex="-1" role="dialog" aria-labelledby="editReservationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editReservationModalLabel">Edit Reservation</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <input type="hidden" id="reservation_id" name="reservation_id">
                                    <div class="form-group">
                                        <label for="edit_full_name">Full Name</label>
                                        <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_vehicle_plate">Vehicle Plate</label>
                                        <input type="text" class="form-control" id="edit_vehicle_plate" name="vehicle_plate" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_duration">Duration (in hours)</label>
                                        <input type="number" class="form-control" id="edit_duration" name="duration" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_price">Price</label>
                                        <input type="number" class="form-control" id="edit_price" name="price" required>
                                    </div>
                                    <button type="submit" name="edit_reservation" class="btn btn-warning">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        // Pre-fill Edit Modal
        $('#editReservationModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var reservation_id = button.data('id');
            var full_name = button.data('name');
            var vehicle_plate = button.data('plate');
            var duration = button.data('duration');
            var price = button.data('price');
            
            var modal = $(this);
            modal.find('.modal-body #reservation_id').val(reservation_id);
            modal.find('.modal-body #edit_full_name').val(full_name);
            modal.find('.modal-body #edit_vehicle_plate').val(vehicle_plate);
            modal.find('.modal-body #edit_duration').val(duration);
            modal.find('.modal-body #edit_price').val(price);
        });
    </script>

</body>
</html>
