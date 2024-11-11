<?php
include('db.php');

// Check if the ID parameter is set and retrieve the user
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
    $user = mysqli_fetch_assoc($result);
}

// Update user logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $vehicle_plate = $_POST['vehicle_plate'];

    // Optional: hash the password only if it has been updated
    $password_hash = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password_hash'];

    // Update query
    $sql = "UPDATE users SET 
            full_name = '$full_name', 
            email = '$email', 
            phone = '$phone', 
            vehicle_plate = '$vehicle_plate', 
            password_hash = '$password_hash' 
            WHERE user_id = $user_id";

    if (mysqli_query($conn, $sql)) {
        $message = "User updated successfully!";
        header("Location: users.php"); // Redirect to manage users page after update
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
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit User</h2>
    <?php if (isset($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>
    <form method="POST">
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="vehicle_plate" class="form-label">Vehicle Plate</label>
            <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate" value="<?php echo $user['vehicle_plate']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password (leave blank if not changing)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
