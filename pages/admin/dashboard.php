<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

include('../../includes/db.php');

// Fetch jobs, internships, and training program details from the database
$query_jobs = "SELECT COUNT(*) AS total_jobs FROM jobs";
$stmt_jobs = $pdo->query($query_jobs);
$total_jobs = $stmt_jobs->fetch(PDO::FETCH_ASSOC)['total_jobs'];

$query_internships = "SELECT COUNT(*) AS total_internships FROM internships";
$stmt_internships = $pdo->query($query_internships);
$total_internships = $stmt_internships->fetch(PDO::FETCH_ASSOC)['total_internships'];

$query_trainings = "SELECT COUNT(*) AS total_trainings FROM training_programs";
$stmt_trainings = $pdo->query($query_trainings);
$total_trainings = $stmt_trainings->fetch(PDO::FETCH_ASSOC)['total_trainings'];

$base_path = '/skillsbridge/';
?>

<?php include("../../includes/head.php"); ?>

<section class="my-5">
    <div class="container">
        <h1 class="text-center">Admin Dashboard</h1>
        <br>

        <!-- Dashboard Summary Section -->
        <div class="row text-center mb-5">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h3><?php echo $total_jobs; ?></h3>
                        <p>Total Jobs</p>
                        <a href="view-job.php" class="btn btn-outline-light">Manage Jobs</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h3><?php echo $total_internships; ?></h3>
                        <p>Total Internships</p>
                        <a href="view-internship.php" class="btn btn-outline-light">Manage Internships</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h3><?php echo $total_trainings; ?></h3>
                        <p>Total Trainings</p>
                        <a href="view-training.php" class="btn btn-outline-light">Manage Trainings</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Management Section -->
        <h2 class="text-center">Job Management</h2>
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
                <!-- Fetch jobs dynamically -->
                <?php
                $query = "SELECT * FROM jobs";
                $stmt = $pdo->query($query);
                while ($job = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
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
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Internship Management Section -->
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
                <!-- Fetch internships dynamically -->
                <?php
                $query = "SELECT * FROM internships";
                $stmt = $pdo->query($query);
                while ($internship = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
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
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Training Program Management Section -->
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
                <!-- Fetch trainings dynamically -->
                <?php
                $query = "SELECT * FROM training_programs";
                $stmt = $pdo->query($query);
                while ($training = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
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
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include('../../includes/footer.php'); ?>
