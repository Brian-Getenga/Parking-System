
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking System - Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
            font-family: 'Poppins', sans-serif;
        }
        .card {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Softer shadow */
            border-radius: 10px; /* Rounded corners */
        }
        .card-body {
            padding: 2rem;
        }
        h3 {
            color: #343a40; /* Darker text for the heading */
        }
        .btn-primary {
            background-color: #007bff; /* Bootstrap primary color */
            border: none;
            transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transition */
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: translateY(-2px); /* Lift effect */
        }
        .text-center a {
            color: #007bff; /* Primary color for links */
        }
        .text-center a:hover {
            text-decoration: underline; /* Underline on hover */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-body">
                <h3 class="text-center mb-4">Login</h3>
                <form action="login_logic.php" method="POST">
                    <div class="form-group">
                        <label for="vehiclePlate">Vehicle Number Plate:</label>
                        <input type="text" id="vehiclePlate" name="vehiclePlate" class="form-control" placeholder="Enter your vehicle number plate" required>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Password:</label>
                        <input type="password" id="loginPassword" name="loginPassword" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
