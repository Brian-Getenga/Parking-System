<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}
?>

<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_seasonal_parking'])) {
    // Add Seasonal Parking Reservation
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $vehicle_plate = $_POST['vehicle_plate'];
    $duration = $_POST['duration'];
    $price = $_POST['price'];
    $reservation_date = $_POST['reservation_date'];

    $sql = "INSERT INTO reservations (user_id, full_name, phone, vehicle_plate, duration, price, reservation_date) 
            VALUES ('$user_id', '$full_name', '$phone', '$vehicle_plate', '$duration', '$price', '$reservation_date')";
    if (mysqli_query($conn, $sql)) {
        $message = "Reservation added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Deleting a reservation
if (isset($_GET['delete_reservation'])) {
    $id = $_GET['delete_reservation'];
    $delete_sql = "DELETE FROM reservations WHERE reservation_id = $id";
    if (mysqli_query($conn, $delete_sql)) {
        $message = "Reservation deleted successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Search functionality
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $result = mysqli_query($conn, "SELECT * FROM reservations WHERE 
        user_id LIKE '%$search_query%' OR 
        vehicle_plate LIKE '%$search_query%' OR 
        full_name LIKE '%$search_query%'");
} else {
    $result = mysqli_query($conn, "SELECT * FROM reservations");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Seasonal Parking Reservations</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Custom styles for layout */
        body { margin: 0; padding: 0; overflow-x: hidden; }
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
        }

        .sidebar { height: 100vh; width: 250px; background-color: #343a40; color: white; position: fixed; }
        .container-fluid { margin-left: 250px; max-width: 1100px; }
        .main-content { padding-top: 80px; }
        .navbar { margin-left: 250px; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h4 class="text-center mb-4 text-info">Admin Dashboard</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="index.php"><i class="bi bi-house-door"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="users.php"><i class="bi bi-person-lines-fill"></i> Manage Users</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="reservations.php"><i class="bi bi-calendar-check"></i> Manage Reservations</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="daily_parking.php"><i class="bi bi-car-front"></i> Manage Daily Parking</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="seasonal_parking.php"><i class="bi bi-calendar4-week"></i> Seasonal Parking</a></li>
        </ul>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <span class="navbar-text me-auto"><strong><?php echo date('l, F j, Y'); ?></strong></span>
            <div class="d-flex align-items-center">
                <span class="navbar-text me-2">Admin</span>
                <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid main-content">
        <h1 class="my-4">Manage Seasonal Parking Reservations</h1>

        <!-- Success/Error Message -->
        <?php if (isset($message)) { ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php } ?>

        <!-- Add New Seasonal Parking Reservation Form -->
        <form method="POST" class="my-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="user_id" class="form-control" placeholder="User ID" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="phone" class="form-control" placeholder="Phone" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="vehicle_plate" class="form-control" placeholder="Vehicle Plate" required>
                </div>
                <div class="col-md-3 mt-3">
                    <input type="text" name="duration" class="form-control" placeholder="Duration" required>
                </div>
                <div class="col-md-3 mt-3">
                    <input type="number" name="price" class="form-control" placeholder="Price" required>
                </div>
                <div class="col-md-3 mt-3">
                    <input type="datetime-local" name="reservation_date" class="form-control" required>
                </div>
                <div class="col-md-3 mt-3">
                    <button type="submit" name="add_seasonal_parking" class="btn btn-primary w-100">Add Reservation</button>
                </div>
            </div>
        </form>

        <!-- Search Bar -->
        <div class="search-bar my-4">
            <form method="GET">
                <input type="text" name="search" class="form-control" value="<?php echo $search_query; ?>" placeholder="Search reservations" required>
                <button type="submit" class="btn btn-primary mt-2 w-100">Search</button>
            </form>
        </div>

        <!-- Reservations Table -->
        <h2 class="my-4">All Seasonal Parking Reservations</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Phone</th>
                    <th>Vehicle Plate</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Reservation Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['vehicle_plate']; ?></td>
                        <td><?php echo $row['duration']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['reservation_date']; ?></td>
                        <td>
                            <a href='edit_seasonal_parking.php?id=<?php echo $row['reservation_id']; ?>' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='?delete_reservation=<?php echo $row['reservation_id']; ?>' class='btn btn-danger btn-sm' onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
