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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_daily_parking'])) {
    // Add Daily Parking reservation
    $user_id = $_POST['user_id'];
    $car_license = $_POST['car_license'];
    $parking_area = $_POST['parking_area'];
    $price = $_POST['price'];
    $payment_method = $_POST['payment_method'];
    $reservation_date = $_POST['reservation_date'];

    $sql = "INSERT INTO daily_parking (user_id, car_license, parking_area, price, payment_method, reservation_date) 
            VALUES ('$user_id', '$car_license', '$parking_area', '$price', '$payment_method', '$reservation_date')";
    if (mysqli_query($conn, $sql)) {
        $message = "Reservation added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Deleting a reservation
if (isset($_GET['delete_daily_parking'])) {
    $id = $_GET['delete_daily_parking'];
    $delete_sql = "DELETE FROM daily_parking WHERE id = $id";
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
    $result = mysqli_query($conn, "SELECT * FROM daily_parking WHERE car_license LIKE '%$search_query%' OR parking_area LIKE '%$search_query%'");
} else {
    $result = mysqli_query($conn, "SELECT * FROM daily_parking");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Daily Parking Reservations</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons for the profile icon -->
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
        }
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
        }


        .container-fluid {
            margin-left: 250px; /* Adjust for sidebar width */
            padding-right: 15px;
            padding-left: 15px;
            max-width: 1100px;
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
        <h1 class="my-4">Manage Daily Parking Reservations</h1>

        <!-- Success/Error Message -->
        <?php if (isset($message)) { ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php } ?>

        <!-- Add New Daily Parking Reservation -->
        <form method="POST" class="my-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="user_id" class="form-control" placeholder="User ID" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="car_license" class="form-control" placeholder="Car License" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="parking_area" class="form-control" placeholder="Parking Area" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="price" class="form-control" placeholder="Price" required>
                </div>
                <div class="col-md-3 mt-3">
                    <input type="text" name="payment_method" class="form-control" placeholder="Payment Method" required>
                </div>
                <div class="col-md-3 mt-3">
                    <input type="date" name="reservation_date" class="form-control" required>
                </div>
                <div class="col-md-3 mt-3">
                    <button type="submit" name="add_daily_parking" class="btn btn-primary w-100">Add Reservation</button>
                </div>
            </div>
        </form>

        <!-- Search Bar -->
        <div class="search-bar my-4">
            <form method="GET">
                <input type="text" name="search" class="form-control" value="<?php echo $search_query; ?>" placeholder="Search reservations by car license or parking area" required>
                <button type="submit" class="btn btn-primary mt-2 w-100">Search</button>
            </form>
        </div>

        <!-- All Daily Parking Reservations Table -->
        <h2 class="my-4">All Daily Parking Reservations</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Car License</th>
                    <th>Parking Area</th>
                    <th>Price</th>
                    <th>Payment Method</th>
                    <th>Reservation Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['user_id']}</td>
                        <td>{$row['car_license']}</td>
                        <td>{$row['parking_area']}</td>
                        <td>{$row['price']}</td>
                        <td>{$row['payment_method']}</td>
                        <td>{$row['reservation_date']}</td>
                        <td>
                            <a href='edit_daily_parking.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='?delete_daily_parking={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this reservation?\")'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
