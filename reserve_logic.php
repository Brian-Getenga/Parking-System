<?php
session_start(); // Start the session to access user data

// Include the database connection
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get user details from session
$userId = $_SESSION['user']['user_id']; // Assuming user_id is stored in the session

// Fetch data from the form
$fullName = $_POST['full_name'];
$email = $_POST['email'];
$vehicleLicense = $_POST['vehicle_license'];
$reservationDate = $_POST['reservation_date'];
$parkingArea = $_POST['parking_area'];
$duration = $_POST['duration'];
$paymentMethod = $_POST['payment_method'];
$notes = $_POST['notes'] ?? '';
$price = $_POST['price'];
$mpesaPhone = $_POST['mpesa_phone'] ?? ''; // Fetch MPesa phone number, if provided

// Prepare SQL to insert reservation data
$sql = "INSERT INTO parking_reservation (user_id, full_name, email, vehicle_license, parking_area, duration, payment_method, reservation_date, notes, price, mpesa_phone) 
        VALUES (:user_id, :full_name, :email, :vehicle_license, :parking_area, :duration, :payment_method, :reservation_date, :notes, :price, :mpesa_phone)";

try {
    // Prepare the statement
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':full_name', $fullName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':vehicle_license', $vehicleLicense);
    $stmt->bindParam(':parking_area', $parkingArea);
    $stmt->bindParam(':duration', $duration);
    $stmt->bindParam(':payment_method', $paymentMethod);
    $stmt->bindParam(':reservation_date', $reservationDate);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':mpesa_phone', $mpesaPhone); // Bind MPesa phone number

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['success'] = "Reservation successfully made!";
    } else {
        $_SESSION['error'] = "Failed to make the reservation. Please try again.";
    }
} catch (PDOException $e) {
    // Catch any database errors
    $_SESSION['error'] = "Database error: " . $e->getMessage();
}

// Redirect back to the reservation page
header("Location: reserve_parking.php");
exit();
?>
