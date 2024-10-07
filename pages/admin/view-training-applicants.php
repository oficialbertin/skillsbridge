<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

include('../../includes/db.php');

// Check if training ID is provided in the URL
if (isset($_GET['id'])) {
    $training_id = $_GET['id'];

    // Fetch the training details for displaying the title (optional)
    $query_training = "SELECT title FROM training_programs WHERE id = ?";
    $stmt_training = $pdo->prepare($query_training);
    $stmt_training->execute([$training_id]);
    $training = $stmt_training->fetch(PDO::FETCH_ASSOC);

    // Fetch applicants for the selected training
    $query_applicants = "SELECT * FROM training_applications WHERE training_id = ?";
    $stmt_applicants = $pdo->prepare($query_applicants);
    $stmt_applicants->execute([$training_id]);
    $applicants = $stmt_applicants->fetchAll(PDO::FETCH_ASSOC);

} else {
    header('Location: view-training.php?message=Training ID not provided');
    exit();
}

// Handle approval/rejection actions
if (isset($_POST['action']) && isset($_POST['application_id'])) {
    $applicationId = $_POST['application_id'];
    $newStatus = $_POST['action'] === 'approve' ? 'short-listed' : 'rejected';

    $updateQuery = "UPDATE training_applications SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute(['status' => $newStatus, 'id' => $applicationId]);

    // Refresh the page to reflect the changes
    header("Location: view-training-applicants.php?id=$training_id");
    exit();
}

$base_path = '/skillsbridge/'; // Base path for linking files
?>

<?php include("../../includes/head.php"); ?>

<section class="my-5">
    <div class="container">
        <h2 class="text-center">
            Applicants for Training: <?php echo htmlspecialchars($training['title']); ?>
        </h2>

        <?php if (empty($applicants)): ?>
            <p class="text-center">No applicants have applied for this training.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Motivation</th>
                        <th>Applied At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applicants as $applicant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($applicant['name']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['motivation']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['applied_at']); ?></td>
                            <td>
                                <!-- Status with color coding -->
                                <?php
                                $status = $applicant['status'];
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
                                    <input type="hidden" name="application_id" value="<?php echo $applicant['id']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php include("../../includes/footer.php"); ?>
