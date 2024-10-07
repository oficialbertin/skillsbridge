<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

include('../../includes/db.php');

$jobId = $_GET['id'] ?? null;
if (!$jobId) {
    header('Location: dashboard.php');
    exit();
}

// Fetch job details
$query = "SELECT title FROM jobs WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $jobId]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch applications for the job
$query = "SELECT * FROM job_applications WHERE job_id = :job_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['job_id' => $jobId]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle approval/rejection actions
if (isset($_POST['action']) && isset($_POST['application_id'])) {
    $applicationId = $_POST['application_id'];
    $newStatus = $_POST['action'] === 'approve' ? 'short-listed' : 'rejected';

    $updateQuery = "UPDATE job_applications SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute(['status' => $newStatus, 'id' => $applicationId]);

    // Refresh the page to reflect the changes
    header("Location: view-application.php?id=$jobId");
    exit();
}
?>

<?php include('../../includes/head.php'); ?>

<section class="my-5">
    <div class="container">
        <h2>Applications for "<?php echo htmlspecialchars($job['title']); ?>"</h2>

        <?php if (count($applications) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Resume</th>
                        <th>Cover Letter</th>
                        <th>Applied At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($application['name']); ?></td>
                            <td><?php echo htmlspecialchars($application['email']); ?></td>
                            <td><a href="../uploads/resumes/<?php echo htmlspecialchars($application['resume']); ?>" target="_blank">View Resume</a></td>
                            <td><?php echo nl2br(htmlspecialchars($application['cover_letter'])); ?></td>
                            <td><?php echo htmlspecialchars($application['submitted_at']); ?></td>
                            <td>
                                <!-- Status with color coding -->
                                <?php
                                $status = $application['status'];
                                $statusColor = 'secondary'; // Default color

                                if ($status == 'pending') {
                                    $statusColor = 'primary'; // Blue
                                } elseif ($status == 'short-listed') {
                                    $statusColor = 'success'; // Green
                                } elseif ($status == 'rejected') {
                                    $statusColor = 'danger'; // Red
                                }
                                ?>
                                <span class="badge bg-<?php echo $statusColor; ?>">
                                    <?php echo ucfirst($status); ?>
                                </span>
                            </td>
                            <td>
                                <!-- Always show actions to allow status change -->
                                <form action="" method="post" style="display: inline;">
                                    <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No applications found for this job.</p>
        <?php endif; ?>
    </div>
</section>

<?php include('../../includes/footer.php'); ?>
