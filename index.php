<!-- /index.php -->
<?php 
include('includes/header.php'); 
?>
<link rel="shortcut icon" href="images/web-icon.webp" type="image/x-icon"> <!-- Ensure the image path is correct -->

<!-- Hero Section -->
<div class="jumbotron text-center py-5 text-white" 
     style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('images/hero-bg.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; height: 100vh;">
    <div class="container">
        <h1 class="display-4 animate__animated animate__fadeInDown">Welcome to Skills Bridge</h1>
        <p class="lead animate__animated animate__fadeInUp">Connecting talent with opportunities for jobs, internships, and training.</p>
        
        <!-- Adjust Get Started button based on login status -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- If the user is logged in, redirect to dashboard or jobs page -->
            <a href="pages/jobs.php" class="btn btn-light btn-lg mt-3 animate__animated animate__pulse animate__infinite">Browse Jobs</a>
        <?php else: ?>
            <!-- If the user is not logged in, show the Register link -->
            <a href="pages/register.php" class="btn btn-light btn-lg mt-3 animate__animated animate__pulse animate__infinite">Get Started</a>
        <?php endif; ?>
    </div>
</div>

<!-- About Us Section -->
<section class="my-5">
    <div class="container text-center">
        <h2 class="text-center mb-4">About Us</h2>
        <p class="text-center lead">Skills Bridge is a platform that connects individuals with job opportunities, internships, and professional training programs to enhance their careers. Our goal is to bridge the gap between job seekers and employers while providing essential skills training.</p>
        <a href="#platform-features" class="btn btn-primary btn-lg mt-4">Learn More</a>
    </div>
</section>

<!-- Platform Features Section -->
<section id="platform-features" class="my-5 bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">What We Offer</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="icon mb-3">
                            <img src="images/job-icon.jpg" alt="Jobs Icon" style="width: 60px;">
                        </div>
                        <h3>Job Listings</h3>
                        <p>Find job opportunities that match your skills and expertise from top companies.</p>
                    </div>
                    <div class="card-footer">
                        <a href="pages/jobs.php" class="btn btn-primary">Browse Jobs</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="icon mb-3">
                            <img src="images/internship-icon.png" alt="Internships Icon" style="width: 60px;">
                        </div>
                        <h3>Internship Opportunities</h3>
                        <p>Gain hands-on experience with trainings offered by leading organizations.</p>
                    </div>
                    <div class="card-footer">
                        <a href="pages/internship.php" class="btn btn-primary">Explore Internship</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="icon mb-3">
                            <img src="images/training-icon.jpg" alt="Training Icon" style="width: 60px;">
                        </div>
                        <h3>Training Programs</h3>
                        <p>Enhance your skills with our professional training programs designed to help you succeed.</p>
                    </div>
                    <div class="card-footer">
                        <a href="pages/training.php" class="btn btn-primary">View Training</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="my-5">
    <div class="container">
        <h2 class="text-center">Success Stories</h2>
        <p class="text-center">Hear from individuals who have transformed their careers through Skills Bridge.</p>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <img src="images/testimonial-user1.jpg" class="card-img-top" alt="User 1">
                    <div class="card-body">
                        <h5 class="card-title">Bright</h5>
                        <p class="card-text">"Skills Bridge helped me land my dream job in tech! The platform is easy to use and filled with valuable resources."</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <img src="images/testimonial-user2.jpg" class="card-img-top" alt="User 2">
                    <div class="card-body">
                        <h5 class="card-title">Muhire</h5>
                        <p class="card-text">"I was able to transition into a new career field thanks to the hands-on training programs offered here."</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <img src="images/testimonial-user3.jpg" class="card-img-top" alt="User 3">
                    <div class="card-body">
                        <h5 class="card-title">Mirelle</h5>
                        <p class="card-text">"The internships I found through Skills Bridge gave me the experience I needed to get hired."</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>

<!-- JavaScript for Smooth Scroll and Animations -->
<script>
    // Smooth scroll to platform features section
    document.querySelector('a[href="#platform-features"]').addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector('#platform-features').scrollIntoView({ behavior: 'smooth' });
    });

    // Additional animations using Animate.css
    document.addEventListener('DOMContentLoaded', function() {
        const elementsToAnimate = document.querySelectorAll('.animate__animated');
        elementsToAnimate.forEach(function(el) {
            el.classList.add('animate__fadeIn');
        });
    });
</script>
