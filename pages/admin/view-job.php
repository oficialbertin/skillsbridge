<!-- /pages/admin/view-job.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

include('../../includes/db.php');

// Fetch jobs from the database
$query = "SELECT * FROM jobs";
$stmt = $pdo->query($query);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
$base_path = '/skillsbridge/'; // Base path for linking files
?>

<?php include("../../includes/head.php")?>

<section class="my-5">
    <div class="container">
        <h2 class="text-center">Job management</h2>
        <ul class="navbar-nav ml-auto">
                    
                        
                    
                </ul>
        <a href="add-job.php" class="btn btn-success mb-3">Add New Job</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($job['title']); ?></td>
                        <td><?php echo htmlspecialchars($job['company']); ?></td>
                        <td><?php echo htmlspecialchars($job['location']); ?></td>
                        <td><?php echo htmlspecialchars($job['category']); ?></td>
                        <td>
                            <a href="edit-job.php?id=<?php echo $job['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete-job.php?id=<?php echo $job['id']; ?>" class="btn btn-danger">Delete</a>
                            <a href="view-application.php?id=<?php echo $job['id']; ?>" class="btn btn-secondary">View Applications</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include('../../includes/footer.php'); ?>
