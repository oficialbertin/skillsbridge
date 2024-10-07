<!-- /pages/admin/edit-job.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

include('../../includes/db.php');
$message = '';
$jobId = $_GET['id'] ?? null;

if (!$jobId) {
    header('Location: dashboard.php');
    exit();
}

// Fetch job details from the database
$query = "SELECT * FROM jobs WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $jobId]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Update job in the database
    $query = "UPDATE jobs SET title = :title, company = :company, location = :location, 
              category = :category, description = :description WHERE id = :id";
    $stmt = $pdo->prepare($query);
    
    if ($stmt->execute([
        'title' => $title,
        'company' => $company,
        'location' => $location,
        'category' => $category,
        'description' => $description,
        'id' => $jobId
    ])) {
        $message = "Job updated successfully!";
    } else {
        $message = "Error updating job. Please try again.";
    }
}
?>

<?php include('../../includes/head.php'); ?>

<section class="my-5">
    <div class="container">
        <h2 class="text-center">Edit Job</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="edit-job.php?id=<?php echo $jobId; ?>">
            <div class="mb-3">
                <label for="title" class="form-label">Job Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($job['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="company" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="company" name="company" value="<?php echo htmlspecialchars($job['company']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($job['category']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Job Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($job['description']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Job</button>
        </form>
    </div>
</section>

<?php include('../../includes/footer.php'); ?>
