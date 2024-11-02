<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Get data from the POST request
$id = $_POST['id'] ?? null;
$type = $_POST['type'] ?? null;

// Validate inputs
if ($id === null || $type === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

// Prepare SQL query based on type
if ($type === 'daily') {
    $stmt = $pdo->prepare("DELETE FROM daily_parking WHERE id = :id AND user_id = :user_id");
} elseif ($type === 'seasonal') {
    $stmt = $pdo->prepare("DELETE FROM parking_reservation WHERE id = :id AND user_id = :user_id");
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid reservation type']);
    exit();
}

// Execute the statement
try {
    $stmt->execute(['id' => $id, 'user_id' => $_SESSION['user']['user_id']]);
    echo json_encode(['success' => true, 'message' => 'Reservation canceled successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to cancel reservation: ' . $e->getMessage()]);
}
?>
