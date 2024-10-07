<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/db.php'); // Include database connection file
$userId = $_SESSION['user_id'];
$message = '';

// Fetch user details
$query = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch job applications
$jobQuery = "SELECT a.*, j.title FROM job_applications a JOIN jobs j ON a.job_id = j.id WHERE a.email = :email";
$stmt = $pdo->prepare($jobQuery);
$stmt->execute(['email' => $user['email']]);
$jobApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch training applications
$trainingQuery = "SELECT ta.*, t.title FROM training_applications ta JOIN training_programs t ON ta.training_id = t.id WHERE ta.email = :email";
$stmt = $pdo->prepare($trainingQuery);
$stmt->execute(['email' => $user['email']]);
$trainingApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch internship applications
$internshipQuery = "SELECT ia.*, i.title FROM internship_applications ia JOIN internships i ON ia.internship_id = i.id WHERE ia.email = :email";
$stmt = $pdo->prepare($internshipQuery);
$stmt->execute(['email' => $user['email']]);
$internshipApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Update profile if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number']; // Capture the full phone number after JavaScript updates
    $bio = $_POST['bio'];
    $skills = $_POST['skills'];

    // Handle profile picture upload and rename it
    $profile_picture = $user['profile_picture']; // Keep current picture by default
    if ($_FILES['profile_picture']['name']) {
        $extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION); // Get file extension
        $profilePictureName = $user['username'] . "_" . $userId . "_profile_picture." . $extension; // Format filename
        $pictureTmp = $_FILES['profile_picture']['tmp_name'];
        $profilePictureDir = "../uploads/profile_pictures/" . $profilePictureName;

        if (move_uploaded_file($pictureTmp, $profilePictureDir)) {
            $profile_picture = $profilePictureName; // Save the new file name to the database
        }
    }

    // Update user profile in the database
    $query = "UPDATE users SET phone_number = :phone_number, full_name = :full_name, bio = :bio, skills = :skills, profile_picture = :profile_picture WHERE id = :id";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute([
        'phone_number' => $phone_number,
        'full_name' => $full_name,
        'bio' => $bio,
        'skills' => $skills,
        'profile_picture' => $profile_picture,
        'id' => $userId
    ])) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile. Please try again.";
    }
}
?>

<?php include('../includes/header.php'); ?>

<section class="my-5">
    <div class="container">
        <h2>Your Profile</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Display Profile Information -->
        <div class="row mb-4">
            <div class="col-md-4">
                <img src="../uploads/profile_pictures/<?php echo htmlspecialchars($user['profile_picture']); ?>" width="150" alt="Profile Picture" class="img-fluid rounded-circle profile-picture">
            </div>
            <div class="col-md-8">
                <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                <p><strong>Bio:</strong> <?php echo htmlspecialchars($user['bio']); ?></p>
                <p><strong>Skills:</strong> <?php echo htmlspecialchars($user['skills']); ?></p>
                <a href="#" class="btn btn-primary" id="edit-profile-btn">Edit Profile</a>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div id="edit-profile-form" style="display:none;">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="country" class="form-label">Country</label>
                    <select id="country" name="country" class="form-select">
                        <option value="+255">Tanzania (+255)</option>
                        <option value="+250">Rwanda (+250)</option>
                        <option value="+257">Burundi (+257)</option>
                        <option value="+256">Uganda (+256)</option>
                        <option value="+254">Kenya (+254)</option>
                        <option value="+243">DRC (+243)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number" required>
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea class="form-control" id="bio" name="bio" rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="skills" class="form-label">Skills</label>
                    <textarea class="form-control" id="skills" name="skills" rows="2"><?php echo htmlspecialchars($user['skills']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                    <?php if ($user['profile_picture']): ?>
                        <p>Current Picture: <img src="../uploads/profile_pictures/<?php echo htmlspecialchars($user['profile_picture']); ?>" width="100" alt="Profile Picture" class="rounded-circle"></p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>

        <!-- Application Details Section -->
        <h3 class="my-4">Your Applications</h3>
        
        <!-- Job Applications Table -->
        <h4>Job Applications</h4>
        <?php if (!empty($jobApplications)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Application Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobApplications as $application): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($application['title']); ?></td>
                            <td><?php echo htmlspecialchars($application['submitted_at']); ?></td>
                            <td><?php echo htmlspecialchars($application['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No job applications found.</p>
        <?php endif; ?>

        <!-- Training Applications Table -->
        <h4>Training Applications</h4>
        <?php if (!empty($trainingApplications)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Training Title</th>
                        <th>Application Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trainingApplications as $application): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($application['title']); ?></td>
                            <td><?php echo htmlspecialchars($application['applied_at']); ?></td>
                            <td><?php echo htmlspecialchars($application['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No training applications found.</p>
        <?php endif; ?>

        <!-- Internship Applications Table -->
        <h4>Internship Applications</h4>
        <?php if (!empty($internshipApplications)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Internship Title</th>
                        <th>Application Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($internshipApplications as $application): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($application['title']); ?></td>
                            <td><?php echo htmlspecialchars($application['applied_at']); ?></td>
                            <td><?php echo htmlspecialchars($application['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No internship applications found.</p>
        <?php endif; ?>

    </div>
</section>

<script>
document.getElementById("edit-profile-btn").addEventListener("click", function() {
    document.getElementById("edit-profile-form").style.display = "block";
});
</script>

<?php include('../includes/footer.php'); ?>
