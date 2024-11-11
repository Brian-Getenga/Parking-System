<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">Parking System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="seasonal_parking.php">Seasonal Parking</a></li>
                <li class="nav-item"><a class="nav-link" href="reserve_parking.php">Reserve Parking</a></li>
                <li class="nav-item"><a class="nav-link" href="parking.php">Parking</a></li>
                <li class="nav-item"><a class="nav-link" href="reservations.php">Reservations</a></li>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> Account
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#userDetailsModal">Profile</a>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- User Details Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailsModalLabel">Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <!-- Profile Image -->
                <img src="Profile.webp" class="rounded-circle mb-3" alt="User Image" width="120" height="120">

                <!-- User Information Display -->
                <div class="user-info mt-3">
                    <h4><?= htmlspecialchars($_SESSION['user']['full_name']); ?></h4>
                    <p class="text-muted mb-2"><?= htmlspecialchars($_SESSION['user']['email']); ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($_SESSION['user']['phone']); ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($_SESSION['user']['address'] ?? 'N/A'); ?></p>
                    <p><strong>License Plate:</strong> <?= htmlspecialchars($_SESSION['user']['vehicle_plate'] ?? 'N/A'); ?></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .user-info h4 {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .user-info p {
        margin: 0.25rem 0;
    }
    .modal-body {
        padding: 2rem;
    }
</style>
