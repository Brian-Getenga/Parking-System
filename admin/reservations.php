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

// Add reservation logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_reservation'])) {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $vehicle_license = $_POST['vehicle_license'];
    $parking_area = $_POST['parking_area'];
    $duration = $_POST['duration'];
    $payment_method = $_POST['payment_method'];
    $reservation_date = $_POST['reservation_date'];
    $notes = $_POST['notes'];
    $mpesa_phone = $_POST['mpesa_phone'];
    $price = $_POST['price'];

    $sql = "INSERT INTO parking_reservation (user_id, full_name, email, vehicle_license, parking_area, duration, payment_method, reservation_date, notes, mpesa_phone, price) 
            VALUES ('$user_id', '$full_name', '$email', '$vehicle_license', '$parking_area', '$duration', '$payment_method', '$reservation_date', '$notes', '$mpesa_phone', '$price')";

    if (mysqli_query($conn, $sql)) {
        $message = "Reservation added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Deleting a reservation
if (isset($_GET['delete_reservation'])) {
    $reservation_id = $_GET['delete_reservation'];
    $delete_sql = "DELETE FROM parking_reservation WHERE id = $reservation_id";
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
    $result = mysqli_query($conn, "SELECT * FROM parking_reservation WHERE full_name LIKE '%$search_query%' OR email LIKE '%$search_query%'");
} else {
    $result = mysqli_query($conn, "SELECT * FROM parking_reservation");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reservations</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons for the icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS for sidebar and navbar -->
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-x: hidden;
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


        .container-fluid {
            margin-left: 250px; /* Adjust for sidebar width */
            padding-right: 15px;
            padding-left: 15px;
            max-width:1100px;
        }

        .main-content {
            padding-top: 80px;
        }

        .navbar {
            margin-left: 250px;
        }

        .form-control, .btn-primary {
            width: 100%;
        }

        .table th, .table td {
            text-align: center;
        }

        .table .btn {
            width: 80px;
        }

        .search-bar input {
            width: 300px;
        }

        @media (max-width: 768px) {
            .container-fluid {
                margin-left: 0; /* Remove sidebar offset on small screens */
            }

            .navbar {
                margin-left: 0; /* Remove navbar offset on small screens */
            }
        }
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
            <!-- Date on the left -->
            <span class="navbar-text me-auto">
                <strong><?php echo date('l, F j, Y'); ?></strong>
            </span>

            <!-- Profile on the right with an icon -->
            <div class="d-flex align-items-center">
                <span class="navbar-text me-2">Admin</span>
                <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid main-content">
        <h1 class="my-4">Manage Reservations</h1>

        <!-- Success/Error Message -->
        <?php if (isset($message)) { ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php } ?>

        <!-- Add Reservation Form -->
        <form method="POST" class="my-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="user_id" class="form-control" placeholder="User ID" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="col-md-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="vehicle_license" class="form-control" placeholder="Vehicle License" required>
                </div>
                <div class="col-md-3 mt-3">
                    <input type="text" name="parking_area" class="form-control" placeholder="Parking Area" required>
                </div>
                <div class="col-md-3 mt-3">
                    <input type="text" name="duration" class="form-control" placeholder="Duration" required>
                </div>
                <div class="col-md-3 mt-3">
                    <input type="text" name="payment_method" class="form-control" placeholder="Payment Method" required>
                </div>
                <div class="col-md-3 mt-3">
                    <input type="date" name="reservation_date" class="form-control" required>
                </div>
                <div class="col-md-3 mt-3">
                    <textarea name="notes" class="form-control" placeholder="Notes"></textarea>
                </div>
                <div class="col-md-3 mt-3">
                    <input type="text" name="mpesa_phone" class="form-control" placeholder="MPESA Phone Number">
                </div>
                <div class="col-md-3 mt-3">
                    <input type="number" name="price" class="form-control" placeholder="Price" required>
                </div>
                <div class="col-md-3 mt-3">
                    <button type="submit" name="add_reservation" class="btn btn-primary w-100">Add Reservation</button>
                </div>
            </div>
        </form>

        <!-- Search Bar -->
        <div class="search-bar my-4">
            <form method="GET">
                <input type="text" name="search" class="form-control" value="<?php echo $search_query; ?>" placeholder="Search reservations by name or email" required>
                <button type="submit" class="btn btn-primary mt-2 w-100">Search</button>
            </form>
        </div>

        <!-- All Reservations Table -->
        <h2 class="my-4">All Reservations</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Vehicle License</th>
                    <th>Parking Area</th>
                    <th>Duration</th>
                    <th>Payment Method</th>
                    <th>Reservation Date</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['vehicle_license']; ?></td>
                        <td><?php echo $row['parking_area']; ?></td>
                        <td><?php echo $row['duration']; ?></td>
                        <td><?php echo $row['payment_method']; ?></td>
                        <td><?php echo $row['reservation_date']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td>
                            <a href="?delete_reservation=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
