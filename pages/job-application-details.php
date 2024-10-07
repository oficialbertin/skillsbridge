<?php
include('../includes/db.php'); // Adjusted include path

$jobId = $_GET['id'] ?? null;
if (!$jobId) {
    header('Location: job-listing.php');
    exit();
}

// Fetch job details
$query = "SELECT * FROM jobs WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $jobId]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $cover_letter = $_POST['cover_letter'];

    // File upload
    $resume = $_FILES['resume'];
    $resumeName = $resume['name'];
    $resumeTmp = $resume['tmp_name'];
    $resumeDir = "../uploads/resumes/" . basename($resumeName);

    if (move_uploaded_file($resumeTmp, $resumeDir)) {
        // Insert application into database
        $query = "INSERT INTO applications (job_id, name, email, resume, cover_letter) 
                  VALUES (:job_id, :name, :email, :resume, :cover_letter)";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute([
            'job_id' => $jobId,
            'name' => $name,
            'email' => $email,
            'resume' => $resumeName,
            'cover_letter' => $cover_letter
        ])) {
            $message = "Application submitted successfully!";
        } else {
            $message = "Error submitting application. Please try again.";
        }
    } else {
        $message = "Error uploading resume. Please try again.";
    }
}
?>

<?php include('../includes/header.php'); ?>

<section class="my-5">
    <div class="container">
        <h2><?php echo htmlspecialchars($job['title']); ?></h2>
        <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
        <p><strong>Description:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>

        <hr>

        <h3>Apply for this job</h3>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Your Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="resume" class="form-label">Upload Resume</label>
                <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
            </div>
            <div class="mb-3">
                <label for="cover_letter" class="form-label">Cover Letter</label>
                <textarea class="form-control" id="cover_letter" name="cover_letter" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Application</button>
        </form>
    </div>
</section>

<?php include('../includes/footer.php'); ?>
