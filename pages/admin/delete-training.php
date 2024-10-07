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

    // Prepare and execute the DELETE query
    $query = "DELETE FROM training_programs WHERE id = ?";
    $stmt = $pdo->prepare($query);

    if ($stmt->execute([$training_id])) {
        // Redirect to the view-training.php page with a success message
        header('Location: view-training.php?message=Training deleted successfully');
        exit();
    } else {
        // If something goes wrong, redirect with an error message
        header('Location: view-training.php?message=Error deleting training');
        exit();
    }
} else {
    // If ID is not set, redirect to view-training.php
    header('Location: view-training.php');
    exit();
}
?>
