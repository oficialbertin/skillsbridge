<?php
include('../includes/header.php'); 
include('../includes/db.php'); 

// Session is already started in header.php
$isLoggedIn = isset($_SESSION['user_id']); // Check if the user is logged in

// Fetch job listings from the database
$query = "SELECT * FROM jobs";
$stmt = $pdo->prepare($query);
$stmt->execute();
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <h1 class="text-center mb-4">Job Listings</h1>
    <div class="row">
        <?php if ($jobs): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($job['title']); ?></h5>
                            <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>

                            <!-- View Details button is always available -->
                            <a href="job-details.php?id=<?php echo $job['id']; ?>" class="btn btn-primary">View Details</a>

                            <?php if ($isLoggedIn): ?>
                                <!-- Show Apply Now button for logged-in users -->
                                <a href="job-application.php?job_id=<?php echo $job['id']; ?>" class="btn btn-success mt-2">Apply Now</a>
                            <?php else: ?>
                                <!-- Non-logged-in users can view details but are prompted to log in to apply -->
                                <p class="mt-2 text-danger">Please <a href="../pages/login.php">login</a> to apply for this job.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No jobs available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
