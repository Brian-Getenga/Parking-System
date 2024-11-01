<?php
require_once 'config.php'; // Database connection

$errors = []; // Array to hold error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $fullName = htmlspecialchars(trim($_POST['fullName'] ?? ''));
    $regVehiclePlate = htmlspecialchars(trim($_POST['regVehiclePlate'] ?? ''));
    $regEmail = filter_var(trim($_POST['regEmail'] ?? ''), FILTER_VALIDATE_EMAIL);
    $regPhone = htmlspecialchars(trim($_POST['regPhone'] ?? ''));
    $regPassword = $_POST['regPassword'] ?? '';

    // Validate required fields
    if (!$fullName) $errors['fullName'] = "Full Name is required.";
    if (!$regVehiclePlate) $errors['regVehiclePlate'] = "Vehicle Plate is required.";
    if (!$regEmail) $errors['regEmail'] = "Valid Email is required.";
    if (!$regPhone) $errors['regPhone'] = "Phone Number is required.";
    if (!$regPassword) $errors['regPassword'] = "Password is required.";

    if (empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($regPassword, PASSWORD_DEFAULT);

        try {
            // Check if email or vehicle plate already exists
            $checkQuery = $pdo->prepare("SELECT * FROM users WHERE email = :email OR vehicle_plate = :vehicle_plate");
            $checkQuery->execute(['email' => $regEmail, 'vehicle_plate' => $regVehiclePlate]);

            if ($checkQuery->rowCount() > 0) {
                $errors['registration'] = "Email or Vehicle Plate already registered.";
            } else {
                // Insert new user record
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
                    exit();
                } else {
                    $errors['registration'] = "Registration failed. Please try again.";
                }
            }
        } catch (PDOException $e) {
            $errors['database'] = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register for Parking System">
    <meta name="keywords" content="parking, registration, vehicle, account">
    <title>Parking System - Register</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f4f6f8;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card-body {
            padding: 2rem;
            background-color: #ffffff;
        }
        h3 {
            color: #333;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
        }
        .error {
            color: red;
            font-size: 0.85rem;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .btn-primary i {
            margin-right: 8px;
        }
        .text-center a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }
        .text-center a:hover {
            color: #0056b3;
        }
        .form-group label {
            font-weight: 500;
            color: #555;
        }
        .form-control {
            font-size: 0.9rem;
            padding: 0.75rem;
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
        }
        .form-control:focus {
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
        }
        .input-group-text {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px 0 0 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card mx-auto">
            <div class="card-body">
                <h3><i class="fas fa-parking"></i> Register for Parking</h3>
                
                <?php if (!empty($errors['registration'])): ?>
                    <div class="error text-center mb-3"><?php echo $errors['registration']; ?></div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fullName">Full Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Full Name" value="<?php echo htmlspecialchars($fullName ?? ''); ?>" required>
                            </div>
                            <?php if (!empty($errors['fullName'])): ?>
                                <div class="error"><?php echo $errors['fullName']; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="regVehiclePlate">Vehicle Number Plate</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                                </div>
                                <input type="text" id="regVehiclePlate" name="regVehiclePlate" class="form-control" placeholder="Vehicle Plate" value="<?php echo htmlspecialchars($regVehiclePlate ?? ''); ?>" required>
                            </div>
                            <?php if (!empty($errors['regVehiclePlate'])): ?>
                                <div class="error"><?php echo $errors['regVehiclePlate']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="regEmail">Email</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" id="regEmail" name="regEmail" class="form-control" placeholder="Enter your email" value="<?php echo htmlspecialchars($regEmail ?? ''); ?>" required>
                        </div>
                        <?php if (!empty($errors['regEmail'])): ?>
                            <div class="error"><?php echo $errors['regEmail']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="regPhone">Phone Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                            </div>
                            <input type="tel" id="regPhone" name="regPhone" class="form-control" placeholder="Enter your phone number" pattern="[0-9]{10}" value="<?php echo htmlspecialchars($regPhone ?? ''); ?>" required>
                        </div>
                        <?php if (!empty($errors['regPhone'])): ?>
                            <div class="error"><?php echo $errors['regPhone']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="regPassword">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" id="regPassword" name="regPassword" class="form-control" placeholder="Create a password" required>
                        </div>
                        <?php if (!empty($errors['regPassword'])): ?>
                            <div class="error"><?php echo $errors['regPassword']; ?></div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-user-plus"></i> Register</button>
                </form>
                <div class="text-center mt-4">
                    Already have an account? <a href="login.php">Log in</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
