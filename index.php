<?php
session_start(); // Start the session to access user data
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking System - Welcome</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero {
            background: url('pexels-pixabay-63294.jpg') no-repeat center center / cover;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            transition: 2s ease;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
            transition: 2s ease;
        }

        .hero:hover .overlay {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            z-index: 2;
        }

        .hero p {
            font-size: 1rem;
            z-index: 2;
        }

        .btn-custom {
            border-radius: 25px;
            padding: 10px 30px;
            font-size: 1.2em;
            transition: background-color 0.3s ease, transform 0.3s ease;
            z-index: 2;
        }

        .btn-primary {
            background-color: #28a745; /* Green */
            border: none;
        }

        .btn-primary:hover {
            background-color: #218838; /* Darker green */
            transform: translateY(-3px);
        }

        .btn-secondary {
            background-color: #007bff; /* Blue */
            border: none;
        }

        .btn-secondary:hover {
            background-color: #0069d9; /* Darker blue */
            transform: translateY(-3px);
        }

        /* Features Section */
        .feature {
            background-color: #f8f9fa;
        }

        .feature-icon {
            font-size: 50px;
            color: #007bff;
        }

        .card {
            border: none;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        /* Testimonials Section */
        .testimonial {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Footer */
        .footer {
            background-color: #343a40;
            color: #fff;
            padding: 20px 0;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.2rem;
            }

            .btn-custom {
                width: 100%;
                margin: 10px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <?php include 'nav_bar.php' ?>
    <!-- Hero Section -->
    <div class="hero">
        <div class="overlay">
            <h1>Welcome to Our Parking System</h1>
            <p>Effortlessly manage your parking needs. Reserve, pay, and track your parking all in one place!</p>
            <div class="btn-group">
                <a href="login.php" class="btn btn-primary btn-custom mx-2 hero-bt">Login</a>
                <a href="register.php" class="btn btn-secondary btn-custom mx-2 hero-bt">Register</a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container text-center mt-5 feature">
        <h2 class="mb-3 ">Features</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <i class="fas fa-parking feature-icon"></i>
                        <h4 class="card-title mt-3">Reserve Your Spot</h4>
                        <p class="card-text">Book your parking spot in advance and avoid the hassle of searching for a space.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <i class="fas fa-credit-card feature-icon"></i>
                        <h4 class="card-title mt-3">Secure Payments</h4>
                        <p class="card-text">Pay your parking fees easily and securely with our online payment system.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <i class="fas fa-mobile-alt feature-icon"></i>
                        <h4 class="card-title mt-3">Mobile Access</h4>
                        <p class="card-text">Manage your parking account anytime, anywhere with our mobile-friendly platform.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section 
    <div class="container text-center mt-5">
        <h2 class="mb-4">What Our Users Say</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="testimonial">
                    <p>"The parking system has made my life so much easier. I can reserve my spot in advance and pay online!"</p>
                    <strong>- Sarah J.</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial">
                    <p>"I love the mobile access feature. I can check my reservations and make payments on the go!"</p>
                    <strong>- Mark T.</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial">
                    <p>"The secure payment option gives me peace of mind. Highly recommend this system!"</p>
                    <strong>- Emily R.</strong>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 Parking System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
