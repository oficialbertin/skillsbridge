<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

include('../../includes/db.php');

// Fetch internships from the database
$query = "SELECT * FROM internships";
$stmt = $pdo->query($query);
$internships = $stmt->fetchAll(PDO::FETCH_ASSOC);
$base_path = '/skillsbridge/'; // Base path for linking files
?>

<?php include("../../includes/head.php")?>

<section class="my-5">
    <div class="container">
        <h2 class="text-center">Internship Management</h2>
        <a href="add-internship.php" class="btn btn-success mb-3">Add New Internship</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Internship Title</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($internships as $internship): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($internship['title']); ?></td>
                        <td><?php echo htmlspecialchars($internship['company']); ?></td>
                        <td><?php echo htmlspecialchars($internship['location']); ?></td>
                        <td><?php echo htmlspecialchars($internship['category']); ?></td>
                        <td>
                            <a href="edit-internship.php?id=<?php echo $internship['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete-internship.php?id=<?php echo $internship['id']; ?>" class="btn btn-danger">Delete</a>
                            <a href="view-internship-applicants.php?id=<?php echo $internship['id']; ?>" class="btn btn-secondary">View Applications</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include('../../includes/footer.php'); ?>
