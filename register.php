<?php include 'nav_bar.php' ?>
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
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 2rem;
        }
        h3 {
            color: #343a40;
        }
        .btn-secondary {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .text-center a {
            color: #007bff;
            text-decoration: none;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
        .form-group label {
            font-weight: 600;
        }
        @media (max-width: 576px) {
            .card {
                margin: 0 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-body">
                <h3 class="text-center mb-4">Create an Account</h3>
                <form action="register_logic.php" method="POST">
                    <div class="form-group">
                        <label for="fullName" class="font-weight-bold">Full Name</label>
                        <input type="text" class="form-control form-control-lg" id="fullName" name="fullName" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label for="regVehiclePlate">Vehicle Number Plate</label>
                        <input type="text" id="regVehiclePlate" name="regVehiclePlate" class="form-control" placeholder="Enter your vehicle number plate" required>
                    </div>
                    <div class="form-group">
                        <label for="regEmail">Email</label>
                        <input type="email" id="regEmail" name="regEmail" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="regPhone">Phone Number</label>
                        <input type="tel" id="regPhone" name="regPhone" class="form-control" placeholder="Enter your phone number" pattern="[0-9]{10}" required>
                        <small class="form-text text-muted">Format: 1234567890</small>
                    </div>
                    <div class="form-group">
                        <label for="regPassword">Password</label>
                        <input type="password" id="regPassword" name="regPassword" class="form-control" placeholder="Create a password" required>
                    </div>
                    <button type="submit" class="btn btn-secondary btn-block">Register</button>
                </form>
                <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
