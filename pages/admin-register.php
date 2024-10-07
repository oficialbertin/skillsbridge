<!-- /pages/admin-register.php -->
<?php
include('../includes/db.php'); // Updated path to include
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'admin'; // Automatically set the role as admin

    // Check if email or username already exists
    $query = "SELECT * FROM users WHERE email = :email OR username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $email, 'username' => $username]);
    
    if ($stmt->rowCount() > 0) {
        $message = "Username or email already exists.";
    } else {
        // Insert new admin user into the database
        $query = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute(['username' => $username, 'email' => $email, 'password' => $password, 'role' => $role])) {
            header('Location: login.php');
            exit();
        } else {
            $message = "Registration failed. Please try again.";
        }
    }
}
?>

<?php include('../includes/header.php'); ?>

<section class="my-5">
    <div class="container">
        <h2 class="text-center">Admin Registration</h2>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="admin-register.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register Admin</button>
        </form>
    </div>
</section>

<?php include('../includes/footer.php'); ?>
