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
    $result = mysqli_query($conn, "SELECT * FROM daily_parking WHERE id = $reservation_id");
    $reservation = mysqli_fetch_assoc($result);
}

// Update reservation logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_daily_parking'])) {
    $user_id = $_POST['user_id'];
    $car_license = $_POST['car_license'];
    $parking_area = $_POST['parking_area'];
    $price = $_POST['price'];
    $payment_method = $_POST['payment_method'];
    $reservation_date = $_POST['reservation_date'];

    // Update query
    $sql = "UPDATE daily_parking SET 
            user_id = '$user_id', 
            car_license = '$car_license',
            parking_area = '$parking_area', 
            price = '$price', 
            payment_method = '$payment_method', 
            reservation_date = '$reservation_date' 
            WHERE id = $reservation_id";

    if (mysqli_query($conn, $sql)) {
        $message = "Reservation updated successfully!";
        header("Location: daily_parking.php"); // Redirect after update
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
    <title>Edit Daily Parking Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Daily Parking Reservation</h2>

    <?php if (isset($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>

    <form method="POST">
        <div class="mb-3">
            <label for="user_id" class="form-label">User ID</label>
            <input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo $reservation['user_id']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="car_license" class="form-label">Car License</label>
            <input type="text" class="form-control" id="car_license" name="car_license" value="<?php echo $reservation['car_license']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="parking_area" class="form-label">Parking Area</label>
            <input type="text" class="form-control" id="parking_area" name="parking_area" value="<?php echo $reservation['parking_area']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="<?php echo $reservation['price']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <input type="text" class="form-control" id="payment_method" name="payment_method" value="<?php echo $reservation['payment_method']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="reservation_date" class="form-label">Reservation Date</label>
            <input type="datetime-local" class="form-control" id="reservation_date" name="reservation_date" value="<?php echo date('Y-m-d\TH:i', strtotime($reservation['reservation_date'])); ?>" required>
        </div>
        <button type="submit" name="update_daily_parking" class="btn btn-primary">Update Reservation</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
