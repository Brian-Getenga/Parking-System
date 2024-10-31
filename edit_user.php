<?php
// edit_user.php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate the form inputs
    $fullName = htmlspecialchars($_POST['fullName']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);

    if (!$email) {
        die("Invalid email address");
    }

    try {
        // Prepare and execute the update query
        $query = "UPDATE Users SET full_name = :fullName, email = :email, phone = :phone, address = :address WHERE id = :userId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':fullName', $fullName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':userId', $_SESSION['user']['id']); // Assuming user ID is stored in session

        $stmt->execute();
        
        // Update the session with the new details
        $_SESSION['user']['full_name'] = $fullName;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['phone'] = $phone;
        $_SESSION['user']['address'] = $address;

        echo "User details updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
