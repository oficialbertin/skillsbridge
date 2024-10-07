<!-- /pages/training.php -->
<?php
include_once '../includes/header.php'; // Include header
include_once '../includes/db.php';     // Include PDO database connection

// Fetch all training programs from the database
$query = "SELECT * FROM training_programs"; // Adjust the table name if necessary
$stmt = $pdo->prepare($query);
$stmt->execute();
$training_programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <h1 class="text-center mb-4">Training Programs</h1>
    <div class="row">
        <?php if ($training_programs): ?>
            <?php foreach ($training_programs as $program): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($program['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($program['description'], 0, 100)) . '...'; ?></p>
                            <a href="training-details.php?id=<?php echo $program['id']; ?>" class="btn btn-primary">Learn More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No training programs available at the moment. Please check back later.</p>
        <?php endif; ?>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
