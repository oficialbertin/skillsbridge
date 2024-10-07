<?php
include('../includes/header.php'); 
include('../includes/db.php'); 

// Session is already started in header.php
$isLoggedIn = isset($_SESSION['user_id']); // Check if the user is logged in

// Fetch internship listings from the database
$query = "SELECT * FROM internships"; // Adjust table name as necessary
$stmt = $pdo->prepare($query);
$stmt->execute();
$internships = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <h1 class="text-center mb-4">Internship Listings</h1>
    <div class="row">
        <?php if ($internships): ?>
            <?php foreach ($internships as $internship): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($internship['title']); ?></h5>
                            <p><strong>Company:</strong> <?php echo htmlspecialchars($internship['company']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($internship['location']); ?></p>

                            <!-- View Details button is always available -->
                            <a href="internship-details.php?id=<?php echo $internship['id']; ?>" class="btn btn-primary">View Details</a>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No internships available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
