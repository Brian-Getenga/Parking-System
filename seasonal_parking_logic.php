<?php
// reserve.php
require_once 'config.php';
session_start(); // Start the session to access user data

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate the form inputs
    $fullName = htmlspecialchars($_POST['fullName']);
    $phone = htmlspecialchars($_POST['phone']);
    $vehiclePlate = htmlspecialchars($_POST['vehicle']);
    $duration = $_POST['duration'];
    $userId = $_SESSION['user']['user_id'] ?? null; // Get user_id from the session
    $price = $_POST['price']; // Assuming price comes based on selected parking area
    //$notes = isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : null;

    // Validate user ID
    if ($userId === null) {
        die("User ID is missing. Please log in to make a reservation.");
    }

    // Validate duration
    $validDurations = ['monthly', 'quarterly', 'yearly'];
    if (!in_array($duration, $validDurations)) {
        die("Invalid parking duration selected.");
    }

    // Get the current date and time for reservation
    $reservationDate = date('Y-m-d H:i:s');

    try {
        // Prepare and execute the insertion
        $query = "INSERT INTO reservations (user_id, full_name, phone, vehicle_plate, duration, price, reservation_date) 
                  VALUES (:userId, :fullName, :phone, :vehiclePlate, :duration, :price, :reservationDate)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':fullName', $fullName);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':vehiclePlate', $vehiclePlate);
        $stmt->bindParam(':duration', $duration);
       // $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':reservationDate', $reservationDate); // Bind the reservation date

        $stmt->execute();

        // Success message
        echo "<script>alert('Reservation successful!'); window.location.href='index.php';</script>";
    } catch (PDOException $e) {
        // Error message
        echo "Error: " . $e->getMessage();
    }
}
?>
