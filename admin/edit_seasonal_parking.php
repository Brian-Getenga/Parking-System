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

// Check if the ID parameter is set and retrieve the reservation
if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM reservations WHERE reservation_id = $reservation_id");
    $reservation = mysqli_fetch_assoc($result);
}

// Update reservation logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_seasonal_parking'])) {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $vehicle_plate = $_POST['vehicle_plate'];
    $duration = $_POST['duration'];
    $price = $_POST['price'];
    $reservation_date = $_POST['reservation_date'];

    // Update query
    $sql = "UPDATE reservations SET 
            user_id = '$user_id', 
            full_name = '$full_name',
            phone = '$phone',
            vehicle_plate = '$vehicle_plate', 
            duration = '$duration', 
            price = '$price', 
            reservation_date = '$reservation_date' 
            WHERE reservation_id = $reservation_id";

    if (mysqli_query($conn, $sql)) {
        $message = "Reservation updated successfully!";
        header("Location: reservations.php"); // Redirect after update
        exit();
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Seasonal Parking Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Seasonal Parking Reservation</h2>

    <?php if (isset($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>

    <form method="POST">
        <div class="mb-3">
            <label for="user_id" class="form-label">User ID</label>
            <input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo $reservation['user_id']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $reservation['full_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $reservation['phone']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="vehicle_plate" class="form-label">Vehicle Plate</label>
            <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate" value="<?php echo $reservation['vehicle_plate']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="duration" class="form-label">Duration</label>
            <input type="text" class="form-control" id="duration" name="duration" value="<?php echo $reservation['duration']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="<?php echo $reservation['price']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="reservation_date" class="form-label">Reservation Date</label>
            <input type="datetime-local" class="form-control" id="reservation_date" name="reservation_date" value="<?php echo date('Y-m-d\TH:i', strtotime($reservation['reservation_date'])); ?>" required>
        </div>
        <button type="submit" name="update_seasonal_parking" class="btn btn-primary">Update Reservation</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
