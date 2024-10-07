<!-- /pages/admin/delete-job.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../pages/login.php');
    exit();
}

include('../../includes/db.php');

$jobId = $_GET['id'] ?? null;

if ($jobId) {
    // Delete job from the database
    $query = "DELETE FROM jobs WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $jobId]);
}

// Redirect back to the dashboard
header('Location: dashboard.php');
exit();
