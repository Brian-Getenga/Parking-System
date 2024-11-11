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
// Delete user logic
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];

    // Deleting the user from the users table
    $sql = "DELETE FROM users WHERE user_id='$user_id'";
    if (mysqli_query($conn, $sql)) {
        $message = "User deleted successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Delete admin logic
if (isset($_GET['delete_admin'])) {
    $admin_id = $_GET['delete_admin'];

    // Deleting the admin from the admin table
    $sql = "DELETE FROM admin WHERE admin_id='$admin_id'";
    if (mysqli_query($conn, $sql)) {
        $message = "Admin deleted successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Add user logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $vehicle_plate = $_POST['vehicle_plate'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (full_name, email, phone, vehicle_plate, password_hash) 
            VALUES ('$full_name', '$email', '$phone', '$vehicle_plate', '$password_hash')";
    if (mysqli_query($conn, $sql)) {
        $message = "User added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Add admin logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_admin'])) {
    $username = $_POST['username'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO admin (username, password_hash) 
            VALUES ('$username', '$password_hash')";
    if (mysqli_query($conn, $sql)) {
        $message = "Admin added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Update user logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $vehicle_plate = $_POST['vehicle_plate'];

    $sql = "UPDATE users SET full_name='$full_name', email='$email', phone='$phone', vehicle_plate='$vehicle_plate' WHERE user_id='$user_id'";
    if (mysqli_query($conn, $sql)) {
        $message = "User updated successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Update admin logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_admin'])) {
    $admin_id = $_POST['admin_id'];
    $username = $_POST['username'];

    // Optionally, update password
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $password_update_sql = $password_hash ? ", password_hash='$password_hash'" : '';

    $sql = "UPDATE admin SET username='$username' $password_update_sql WHERE admin_id='$admin_id'";
    if (mysqli_query($conn, $sql)) {
        $message = "Admin updated successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Fetch all users and admins
$users_result = mysqli_query($conn, "SELECT * FROM users");
$admins_result = mysqli_query($conn, "SELECT * FROM admin");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users and Admins</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
            margin-left: 250px;
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

        .modal-header {
            background-color: #343a40;
            color: white;
        }

        @media (max-width: 768px) {
            .container-fluid {
                margin-left: 0;
            }

            .navbar {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

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

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
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

    <div class="container-fluid main-content">
        <h1 class="my-4">Manage Users</h1>

        <?php if (isset($message)) { ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php } ?>

        <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="bi bi-person-plus"></i> Add User</button>
        <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#addAdminModal"><i class="bi bi-person-plus"></i> Add Admin</button>

        <h2 class="my-4">Manage Admins</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($admin = mysqli_fetch_assoc($admins_result)) {
                    echo "<tr>
                        <td>{$admin['username']}</td>
                        <td>
                            <a href='#' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editAdminModal' data-id='{$admin['admin_id']}' data-username='{$admin['username']}'>Edit</a>
                            <a href='?delete_admin={$admin['admin_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this admin?\")'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <h2 class="my-4">Manage Users</h2>
        <form method="GET" class="search-bar my-4">
            <input type="text" name="search" class="form-control" value="<?php echo $search_query; ?>" placeholder="Search users by name or email" required>
            <button type="submit" class="btn btn-primary mt-2 w-100">Search</button>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Vehicle Plate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($user = mysqli_fetch_assoc($users_result)) {
                    echo "<tr>
                        <td>{$user['full_name']}</td>
                        <td>{$user['email']}</td>
                        <td>{$user['phone']}</td>
                        <td>{$user['vehicle_plate']}</td>
                        <td>
                            <a href='#' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editUserModal' data-id='{$user['user_id']}' data-name='{$user['full_name']}' data-email='{$user['email']}' data-phone='{$user['phone']}' data-vehicle_plate='{$user['vehicle_plate']}'>Edit</a>
                            <a href='?delete_user={$user['user_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="users.php">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="mb-3">
                            <label for="edit_full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_vehicle_plate" class="form-label">Vehicle Plate</label>
                            <input type="text" class="form-control" id="edit_vehicle_plate" name="vehicle_plate" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="edit_user" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Admin Modal -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAdminModalLabel">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="users.php">
                        <input type="hidden" name="admin_id" id="edit_admin_id">
                        <div class="mb-3">
                            <label for="edit_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="edit_admin" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="users.php">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="vehicle_plate" class="form-label">Vehicle Plate</label>
                            <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Admin Modal -->
    <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAdminModalLabel">Add New Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="users.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="add_admin" class="btn btn-primary">Add Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Populate Edit User Modal with existing user data
        document.querySelectorAll('.btn-warning').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                const fullName = this.dataset.name;
                const email = this.dataset.email;
                const phone = this.dataset.phone;
                const vehiclePlate = this.dataset.vehicle_plate;

                document.getElementById('edit_user_id').value = userId;
                document.getElementById('edit_full_name').value = fullName;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_phone').value = phone;
                document.getElementById('edit_vehicle_plate').value = vehiclePlate;
            });
        });

        // Populate Edit Admin Modal with existing admin data
        document.querySelectorAll('.btn-warning').forEach(button => {
            button.addEventListener('click', function () {
                const adminId = this.dataset.id;
                const username = this.dataset.username;

                document.getElementById('edit_admin_id').value = adminId;
                document.getElementById('edit_username').value = username;
            });
        });
    </script>
</body>
</html>
