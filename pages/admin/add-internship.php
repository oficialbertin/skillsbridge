<?php
session_start();
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

include('../../includes/db.php'); // Include your database connection file
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Insert internship into the database
    $query = "INSERT INTO internships (title, company, location, category, description) 
              VALUES (:title, :company, :location, :category, :description)";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute([
        'title' => $title,
        'company' => $company,
        'location' => $location,
        'category' => $category,
        'description' => $description
    ])) {
        $message = "Internship program added successfully!";
        header("Location: dashboard.php"); // Redirect to the dashboard after successful addition
    } else {
        $message = "Error adding internship program. Please try again.";
    }
}
?>

<?php include("../../includes/head.php"); ?>

<section class="my-5">
    <div class="container">
        <h2>Add a New Internship Program</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Internship Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="company" class="form-label">Company</label>
                <input type="text" class="form-control" id="company" name="company" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Internship Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Internship</button>
        </form>
    </div>
</section>

<?php include('../../includes/footer.php'); ?>
