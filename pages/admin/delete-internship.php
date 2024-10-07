<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../pages/login.php');
    exit();
}

include('../../includes/db.php');

$internshipId = $_GET['id'] ?? null;

if ($internshipId) {
    // Prepare and execute the DELETE query
    $query = "DELETE FROM internships WHERE id = :id";
    $stmt = $pdo->prepare($query);

    if ($stmt->execute(['id' => $internshipId])) {
        // Redirect to the view-internship.php page with a success message
        header('Location: view-internship.php?message=Internship deleted successfully');
        exit();
    } else {
        // If something goes wrong, redirect with an error message
        header('Location: view-internship.php?message=Error deleting internship');
        exit();
    }
} else {
    // If ID is not set, redirect to view-internship.php
    header('Location: view-internship.php');
    exit();
}
?>
