
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

    // Fetch the training details from the database
    $query = "SELECT * FROM training_programs WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$training_id]);
    $training = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$training) {
        // If no training found with that ID, redirect back
        header('Location: view-training.php?message=Training not found');
        exit();
    }
} else {
    header('Location: view-training.php');
    exit();
}

// Handle form submission for editing training
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Update query
    $query = "UPDATE training_programs SET title = ?, description = ?, start_date = ?, end_date = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);

    if ($stmt->execute([$title, $description, $start_date, $end_date, $training_id])) {
        header('Location: view-training.php?message=Training updated successfully');
        exit();
    } else {
        $error_message = "Failed to update training. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css"> <!-- Link to your stylesheet -->
    <title>Edit Training</title>
</head>
<body>
    <div class="container my-5">
        <h1>Edit Training Program</h1>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="edit-training.php?id=<?php echo $training_id; ?>" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($training['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($training['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($training['start_date']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($training['end_date']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Training</button>
            <a href="view-training.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
