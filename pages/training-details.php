<?php
include_once '../includes/header.php'; // Include header
include_once '../includes/db.php';     // Include PDO connection

// Session is already started in header.php, no need to start it again

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

// Check if a training program is requested
if (isset($_GET['id'])) {
    $training_id = $_GET['id'];

    // Fetch training program details
    $query = "SELECT * FROM training_programs WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$training_id]);
    $training = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $training_id = $_POST['training_id'];
    $name = $_POST['name'];

    // Check if a PDF file is uploaded
    if (isset($_FILES['motivation_pdf']) && $_FILES['motivation_pdf']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['motivation_pdf']['tmp_name'];
        $file_name = $_FILES['motivation_pdf']['name'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        // Ensure the file is a PDF
        if (strtolower($file_extension) === 'pdf') {
            // Generate a unique file name to avoid conflicts
            $new_file_name = uniqid() . '.' . $file_extension;
            $upload_dir = '../uploads/';
            $upload_file_path = $upload_dir . $new_file_name;

            // Move the uploaded file to the uploads directory
            if (move_uploaded_file($file_tmp_path, $upload_file_path)) {
                // Insert the application into the training_applications table
                $query = "INSERT INTO training_applications (user_id, training_id, name, email, motivation) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$user_id, $training_id, $name, $user_email, $new_file_name]);

                // Redirect back to the training details page with success message
                header("Location: training-details.php?id=$training_id&application=success");
                exit();
            } else {
                echo '<p class="text-danger">File upload failed. Please try again.</p>';
            }
        } else {
            echo '<p class="text-danger">Only PDF files are allowed for the cover letter.</p>';
        }
    } else {
        echo '<p class="text-danger">Please upload your cover letter as a PDF.</p>';
    }
}
?>

<div class="container my-5">
    <?php if (isset($training)): ?>
        <h1 class="mb-4"><?php echo htmlspecialchars($training['title']); ?></h1>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($training['description']); ?></p>
        <p><strong>Start Date:</strong> <?php echo $training['start_date']; ?></p>
        <p><strong>End Date:</strong> <?php echo $training['end_date']; ?></p>

        <!-- Apply Form -->
        <h3>Apply for this Training Program</h3>

        <form action="training-details.php?id=<?php echo $training['id']; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="training_id" value="<?php echo $training['id']; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $_SESSION['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Your Email (auto-filled)</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user_email; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="motivation_pdf" class="form-label">Upload Motivation Letter (PDF only)</label>
                <input type="file" class="form-control" id="motivation_pdf" name="motivation_pdf" accept="application/pdf" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Application</button>
        </form>

        <!-- Display success message if the application was submitted -->
        <?php if (isset($_GET['application']) && $_GET['application'] === 'success'): ?>
            <p class="text-success mt-3">Your application has been submitted successfully!</p>
        <?php endif; ?>
    <?php else: ?>
        <p class="text-center">Training program not found.</p>
    <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>
