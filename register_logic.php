<?php
require_once 'config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $fullName = htmlspecialchars(trim($_POST['fullName'] ?? ''));
    $regVehiclePlate = htmlspecialchars(trim($_POST['regVehiclePlate'] ?? ''));
    $regEmail = filter_var(trim($_POST['regEmail'] ?? ''), FILTER_VALIDATE_EMAIL);
    $regPhone = htmlspecialchars(trim($_POST['regPhone'] ?? ''));
    $regPassword = $_POST['regPassword'] ?? '';

    // Validate required fields
    if (!$fullName || !$regVehiclePlate || !$regEmail || !$regPhone || !$regPassword) {
        echo "<script>alert('All fields are required. Please fill out all fields.'); window.location.href='register.php';</script>";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($regPassword, PASSWORD_DEFAULT);

    try {
        // Check if email or vehicle plate already exists in the database
        $checkQuery = $pdo->prepare("SELECT * FROM users WHERE email = :email OR vehicle_plate = :vehicle_plate");
        $checkQuery->execute(['email' => $regEmail, 'vehicle_plate' => $regVehiclePlate]);

        if ($checkQuery->rowCount() > 0) {
            echo "<script>alert('Email or Vehicle Plate already registered.'); window.location.href='register.php';</script>";
        } else {
            // Insert new user record into Users table
            $query = "INSERT INTO users (full_name, email, phone, vehicle_plate, password_hash) VALUES (:full_name, :email, :phone, :vehicle_plate, :password_hash)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'full_name' => $fullName,
                'email' => $regEmail,
                'phone' => $regPhone,
                'vehicle_plate' => $regVehiclePlate,
                'password_hash' => $hashedPassword
            ]);

            if ($stmt->rowCount()) {
                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Registration failed. Please try again.'); window.location.href='register.php';</script>";
            }
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "<script>alert('Database error: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='register.php';</script>";
    }
}
?>
