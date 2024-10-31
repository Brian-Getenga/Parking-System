<?php
session_start();
require_once 'config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize
    $vehiclePlate = trim($_POST['vehiclePlate']);
    $password = trim($_POST['loginPassword']);

    // Check if input values are not empty
    if (empty($vehiclePlate) || empty($password)) {
        echo "<script>alert('Please enter both vehicle number plate and password.'); window.location.href='login.php';</script>";
        exit();
    }

    try {
        // Prepare and execute a statement to fetch user data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE vehicle_plate = :vehicle_plate");
        $stmt->execute(['vehicle_plate' => $vehiclePlate]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify user credentials
        if ($user && password_verify($password, $user['password_hash'])) {
            // Store user details in the session
            $_SESSION['user'] = [
                'user_id' => $user['user_id'], // Store user_id in session
                'full_name' => $user['full_name'],
                'email' => $user['email'], // Add email to session
                'vehicle_plate' => $user['vehicle_plate'],
                // Add other user details as needed
            ];

            // Redirect to the reservation page or home page
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Invalid vehicle number plate or password.'); window.location.href='login.php';</script>";
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "<script>alert('Database error: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='login.php';</script>";
    }
}
?>
