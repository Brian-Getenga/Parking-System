<?php
require_once 'config.php'; // Database connection

class RegisterModel {
    private $pdo;
    public $errors = [];

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function validateAndRegister($data) {
        $fullName = htmlspecialchars(trim($data['fullName'] ?? ''));
        $regVehiclePlate = htmlspecialchars(trim($data['regVehiclePlate'] ?? ''));
        $regEmail = filter_var(trim($data['regEmail'] ?? ''), FILTER_VALIDATE_EMAIL);
        $regPhone = htmlspecialchars(trim($data['regPhone'] ?? ''));
        $regPassword = $data['regPassword'] ?? '';

        // Validate required fields
        if (!$fullName || !$regVehiclePlate || !$regEmail || !$regPhone || !$regPassword) {
            $this->errors[] = "All fields are required.";
        }

        // Additional validations
        if (!filter_var($regEmail, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Invalid email format.";
        }
        if (!preg_match('/^[0-9]{10}$/', $regPhone)) {
            $this->errors[] = "Invalid phone number format. Please enter 10 digits.";
        }

        // Return if there are validation errors
        if (!empty($this->errors)) {
            return false;
        }

        // Check for existing user with the same email or vehicle plate
        $existingUser = $this->checkExistingUser($regEmail, $regVehiclePlate);
        if ($existingUser) {
            $this->errors[] = "Email or Vehicle Plate already registered.";
            return false;
        }

        // Hash the password
        $hashedPassword = password_hash($regPassword, PASSWORD_DEFAULT);

        // Insert the new user
        return $this->insertUser($fullName, $regEmail, $regPhone, $regVehiclePlate, $hashedPassword);
    }

    private function checkExistingUser($email, $vehiclePlate) {
        $query = "SELECT * FROM users WHERE email = :email OR vehicle_plate = :vehicle_plate";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email, 'vehicle_plate' => $vehiclePlate]);
        return $stmt->fetch();
    }

    private function insertUser($fullName, $email, $phone, $vehiclePlate, $passwordHash) {
        try {
            $query = "INSERT INTO users (full_name, email, phone, vehicle_plate, password_hash)
                      VALUES (:full_name, :email, :phone, :vehicle_plate, :password_hash)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'vehicle_plate' => $vehiclePlate,
                'password_hash' => $passwordHash
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->errors[] = "Database error: " . htmlspecialchars($e->getMessage());
            return false;
        }
    }
}
