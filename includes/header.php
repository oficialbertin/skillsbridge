<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session if not already started
}
$isLoggedIn = isset($_SESSION['user_id']); // Check if the user is logged in
$base_path = '/skillsbridge/'; // Base path for linking files
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="../images/web-icon.webp" type="image/x-icon">


    <title>Skills Bridge</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="<?php echo $base_path; ?>index.php">Skills Bridge</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>pages/jobs.php">Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>pages/training.php">Training</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>pages/internship.php">Internship</a>
                    </li>
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_path; ?>pages/profile.php">Profile</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto"> <!-- Changed ml-auto to ms-auto for proper Bootstrap 5 alignment -->
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item">
                            <span class="nav-link">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span> <!-- Added htmlspecialchars for security -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_path; ?>pages/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_path; ?>pages/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_path; ?>pages/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Loading Spinner -->
<div id="loader" class="loading-spinner">
    <div class="spinner"></div>
</div>


<script>

// JavaScript to hide the loader once the page is fully loaded
window.addEventListener('load', function() {
    // Select the loader div by ID
    var loader = document.getElementById('loader');

    // Hide the loader once the page is loaded
    loader.style.visibility = 'hidden';
    loader.style.opacity = '0'; // Optional: fade out effect
});
</script>
</body>
</html>
