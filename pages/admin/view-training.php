<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

include('../../includes/db.php');

// Fetch training programs from the database
$query = "SELECT * FROM training_programs";
$stmt = $pdo->query($query);
$trainings = $stmt->fetchAll(PDO::FETCH_ASSOC);
$base_path = '/skillsbridge/'; // Base path for linking files
?>

<?php include("../../includes/head.php")?>

<section class="my-5">
    <div class="container">
        <h2 class="text-center">Training Program Management</h2>
        <a href="add-training.php" class="btn btn-success mb-3">Add New Training</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Training Title</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (isset($_GET['message'])): ?>
    <div class="alert alert-info">
        <?php echo htmlspecialchars($_GET['message']); ?>
    </div>
<?php endif; ?>

                <?php foreach ($trainings as $training): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($training['title']); ?></td>
                        <td><?php echo htmlspecialchars($training['description']); ?></td>
                        <td><?php echo htmlspecialchars($training['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($training['end_date']); ?></td>
                        <td>
                            <a href="edit-training.php?id=<?php echo $training['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete-training.php?id=<?php echo $training['id']; ?>" class="btn btn-danger">Delete</a>
                            <a href="view-training-applicants.php?id=<?php echo $training['id']; ?>" class="btn btn-secondary">View Applicants</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include('../../includes/footer.php'); ?>
