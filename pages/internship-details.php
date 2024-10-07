<?php
include_once '../includes/header.php'; // Include header
include_once '../includes/db.php';     // Include PDO connection

// Ensure session is started to check user login status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's email from the database
$query = "SELECT email FROM users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // If user not found, force logout
    header('Location: ../pages/logout.php');
    exit();
}

$user_email = $user['email'];

// Fetch internship details if an internship is requested
if (isset($_GET['id'])) {
    $internship_id = $_GET['id'];

    // Fetch internship details
    $query = "SELECT * FROM internships WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$internship_id]);
    $internship = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission for internship application
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $internship_id = $_POST['internship_id'];

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

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($_FILES['cover_letter']['tmp_name'], $file_path)) {
                // Insert the application details along with the file path into the database
                $query = "INSERT INTO internship_applications (internship_id, name, email, cover_letter) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$internship_id, $name, $user_email, $file_path]);

                // Redirect to a success page or back to internship details with success message
                header("Location: internship-details.php?id=$internship_id&application=success");
                exit();
            } else {
                $error_message = "Failed to upload cover letter. Please try again.";
            }
        } else {
            $error_message = "Only PDF files are allowed for the cover letter.";
        }
    } else {
        $error_message = "Please upload your cover letter as a PDF file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Application</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Link to your stylesheet -->
</head>
<body>
<div class="container my-5">
    <?php if ($internship): ?>
        <h1 class="mb-4"><?php echo htmlspecialchars($internship['title']); ?></h1>
        <p><strong>Company:</strong> <?php echo htmlspecialchars($internship['company']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($internship['location']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($internship['category']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($internship['description']); ?></p>
        <p><strong>Posted On:</strong> <?php echo $internship['created_at']; ?></p>

        <!-- Apply Now Button -->
        <button class="btn btn-primary" id="apply-now-btn">Apply Now</button>

        <!-- Apply Form (Initially Hidden) -->
        <div id="apply-form" style="display: none;">
            <h3>Apply for this internship</h3>
            <form action="internship-details.php?id=<?php echo $internship['id']; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="internship_id" value="<?php echo $internship['id']; ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $_SESSION['username']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Your Email (auto-filled)</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user_email; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="cover_letter" class="form-label">Upload Cover Letter (PDF only)</label>
                    <input type="file" class="form-control" id="cover_letter" name="cover_letter" accept="application/pdf" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit Application</button>
            </form>
        </div>

        <!-- Display error message if there is any -->
        <?php if (isset($error_message)): ?>
            <p class="text-danger mt-3"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Display success message if the application was submitted -->
        <?php if (isset($_GET['application']) && $_GET['application'] === 'success'): ?>
            <p class="text-success mt-3">Your application has been submitted successfully!</p>
        <?php endif; ?>
    <?php else: ?>
        <p class="text-center">Internship not found.</p>
    <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>

<!-- Script to show the form when the button is clicked -->
<script>
document.getElementById('apply-now-btn').addEventListener('click', function() {
    document.getElementById('apply-form').style.display = 'block'; // Show the form
    this.style.display = 'none'; // Hide the "Apply Now" button
});
</script>
</body>
</html>
