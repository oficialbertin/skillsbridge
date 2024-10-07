<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../pages/login.php');
    exit();
}

include_once '../../includes/db.php'; // Include the PDO database connection
include("../../includes/head.php");   // Include header

// Initialize error or success messages
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    // Validate the data (optional, but recommended)
    if (!empty($title) && !empty($description) && !empty($start_date) && !empty($end_date)) {
        try {
            // Insert into the database
            $query = "INSERT INTO training_programs (title, description, start_date, end_date) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);

            // Execute the query with form data
            if ($stmt->execute([$title, $description, $start_date, $end_date])) {
                // Redirect to the training management page with a success message
                header('Location: view-training.php?message=Training program added successfully');
                exit();
            } else {
                $message = 'Error adding training program. Please try again.';
            }
        } catch (Exception $e) {
            // Handle any errors (such as database connection issues)
            $message = 'Error: ' . $e->getMessage();
        }
    } else {
        $message = 'Please fill out all required fields.';
    }
}
?>

<div class="container my-5">
    <h1>Add New Training Program</h1>

    <!-- Display error or success message -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="add-training.php">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Training</button>
    </form>
</div>

<?php include_once '../../includes/footer.php'; ?>
