<?php
include_once '../includes/header.php'; // Include header
include_once '../includes/db.php';     // Include PDO connection

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

$userDetails = []; // Initialize an array to store user details

// Fetch user details if logged in
if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];

    // Fetch the user's name and email from the 'users' table
    $userQuery = "SELECT username, email FROM users WHERE id = ?";
    $userStmt = $pdo->prepare($userQuery);
    $userStmt->execute([$user_id]);
    $userDetails = $userStmt->fetch(PDO::FETCH_ASSOC); // Fetch user details as an associative array
}

// Initialize job details
$job = null;

// Get the job ID from the URL parameter
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Fetch job details from the database
    $query = "SELECT * FROM jobs WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$job_id]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission and file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isLoggedIn) {
    $job_id = $_POST['job_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Check if a file was uploaded
    if (isset($_FILES['cover_letter']) && $_FILES['cover_letter']['error'] === 0) {
        $allowed_ext = ['pdf']; // Allow only PDF files
        $file_ext = pathinfo($_FILES['cover_letter']['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($file_ext), $allowed_ext)) {
            // Define upload path
            $upload_dir = '../uploads/';
            // Create the new file name in the format: name(user)_cover_letter_user_id(db).pdf
            $file_name = $name . '_cover_letter_' . $user_id . '.' . $file_ext;
            $file_path = $upload_dir . $file_name;

            // Move the uploaded file to the defined directory
            if (move_uploaded_file($_FILES['cover_letter']['tmp_name'], $file_path)) {
                // Insert the application details into the database
                $query = "INSERT INTO job_applications (job_id, user_id, name, email, cover_letter) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$job_id, $user_id, $name, $email, $file_name]);

                $successMessage = "Application submitted successfully!";
            } else {
                $errorMessage = "Failed to upload cover letter.";
            }
        } else {
            $errorMessage = "Invalid file type. Only PDF files are allowed.";
        }
    } else {
        $errorMessage = "No file uploaded or an error occurred.";
    }
}
?>

<div class="container my-5">
    <?php if ($job): ?>
        <h1 class="mb-4"><?php echo htmlspecialchars($job['title']); ?></h1>
        <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
        <p><?php echo htmlspecialchars($job['description']); ?></p>
        <p><strong>Posted On:</strong> <?php echo htmlspecialchars($job['posted_at']); ?></p>

        <!-- Apply Form -->
        <h3>Apply for this position</h3>

        <!-- Display success or error messages -->
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php elseif (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if ($isLoggedIn && $userDetails): ?>
            <!-- Show form if user is logged in and details are fetched -->
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['id']); ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($userDetails['username']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Your Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userDetails['email']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="cover_letter" class="form-label">Upload Cover Letter (PDF only)</label>
                    <input type="file" class="form-control" id="cover_letter" name="cover_letter" accept=".pdf" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit Application</button>
            </form>
        <?php else: ?>
            <!-- Show message prompting the user to log in -->
            <p class="text-danger">Please <a href="../pages/login.php">log in</a> to submit your application.</p>
        <?php endif; ?>
    <?php else: ?>
        <p class="text-center">Job not found.</p>
    <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>
