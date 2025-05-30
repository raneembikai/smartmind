<?php
session_start();
include("inc/connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $con->prepare("SELECT name, email, dob FROM logintb WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $dob);
$stmt->fetch();
$stmt->close();

$quotes = [
    "The more you practice logic, the better your brain works.",
    "Memory is the key to understanding; logic is the key to solving problems.",
    "A clear mind leads to better decisions and sharper thinking.",
    "Every problem has a solution; it’s just a matter of finding it.",
    "Critical thinking is the key to success in life and work."
];

// Select a random quote each time
$randomQuote = $quotes[array_rand($quotes)];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>SMARTMIND ACADEMY</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link href="assets/css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="darktheme.css">
    <script type="text/javascript" src="darkmode.js" defer></script>
    <script id="messenger-widget-b" src="https://cdn.botpenguin.com/website-bot.js" defer>6814def45987761472635840,6814de68cebc80433966ecd0</script>

    
    <style>
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            right: -350px; /* Increased width to accommodate form */
            width: 350px;
            height: 100%;
            background-color: #f8f9fa;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease-in-out;
            z-index: 1000;
            padding: 20px;
            overflow-y: auto; /* In case the form gets long */
        }

        .sidebar.open {
            right: 0;
        }

        .sidebar h2 {
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .sidebar p {
            margin-bottom: 10px;
        }

        .sidebar .btn-edit {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .sidebar .btn-edit:hover {
            background-color: #0056b3;
        }

        .profile-icon-container {
            cursor: pointer;
        }

        .sidebar .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 1.5em;
            color: #555;
            border: none;
            background: none;
            padding: 0;
        }

        .sidebar .close-btn:hover {
            color: #000;
        }

        .edit-profile-form {
            display: none; /* Initially hidden */
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-save {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }

        .btn-save:hover {
            background-color: #1e7e34;
        }

        .btn-cancel {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-cancel:hover {
            background-color: #c82333;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="index.html" class="logo d-flex align-items-center me-auto">
                <h1 class="sitename">SMARTMIND</h1>

            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Home<br></a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="reviewspro.php">feedbacks</a></li>
                    <li><a href="gamesPro.php">Games</a></li>
                    <li><a href="news.php">News</a></li>
                    <li><a href="logout.php">Logout</a></li>
                    <li class="profile-icon-container">
                        <img src="assets/img/user.png" alt="Profile" style="width: 24px; height: 24px; border-radius: 50%; margin-right: 5px;">
                    </li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

        </div>
    </header>

    <div class="sidebar">
        <button class="close-btn">&times;</button>
        <h2>Profile Information</h2>
        <div id="profileInfo">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($dob); ?></p>
            <button class="btn-edit">Edit Profile</button>
        </div>

        <div id="editProfileForm" class="edit-profile-form">
            <h2>Edit Your Information</h2>
            <div id="update-messages">
                <?php if (isset($_SESSION['update_error'])): ?>
                    <p class="error-message"><?php echo $_SESSION['update_error']; unset($_SESSION['update_error']); ?></p>
                <?php endif; ?>
                <?php if (isset($_SESSION['update_success'])): ?>
                    <p class="success-message"><?php echo $_SESSION['update_success']; unset($_SESSION['update_success']); ?></p>
                <?php endif; ?>
            </div>
            <form id="updateProfileForm" action="update_profile.php" method="post">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
                </div>
                <button type="submit" class="btn-save">Save Changes</button>
                <button type="button" class="btn-cancel">Cancel</button>
            </form>
        </div>
    </div>

    <main class="main">

        <section id="hero" class="hero section">

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
                        <h1>Train your logic with Smartmind Academy</h1>
                        <br>
                        <div class="d-flex">
                            <a href="iqtest.php" class="btn-get-started">Take IQ Test</a>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 hero-img">
                        <img src="assets/img/mind.jpg" class="img-fluid animated" alt="">
                    </div>
                </div>

            </div>

        </section></div>

        </div>

    </section><section id="about" class="about section">

    <div class="container section-title" data-aos="fade-up">
        <h2>About Us</h2>
        <p>Nurturing logical minds, one puzzle at a time.</p>
    </div><div class="container">

        <div class="row gy-5">

            <div class="content col-xl-5 d-flex flex-column" data-aos="fade-up" data-aos-delay="100">
                <h3>Empowering Logic, Critical Thinking & Mental Fitness</h3>
                <p>At Logic Trainer, we believe in the power of reasoning and mental agility. Our platform is designed to help individuals of all ages sharpen their logic, problem-solving, and cognitive abilities through engaging challenges, brain games, and adaptive IQ assessments.</p>
            </div>

            <div class="col-xl-7" data-aos="fade-up" data-aos-delay="200">
                <div class="row gy-4">

                    <div class="col-md-6 icon-box position-relative">
                        <i class="bi bi-gear-wide-connected"></i>
                        <h4><a class="stretched-link">Logic & Puzzles</a></h4>
                        <p>Stimulate your brain with logic games that challenge your reasoning and deduction skills.</p>
                    </div><div class="col-md-6 icon-box position-relative">
                        <i class="bi bi-bar-chart-line"></i>
                        <h4><a class="stretched-link">IQ Assessments</a></h4>
                        <p>Take smart tests and track cognitive improvement across multiple metrics over time.</p>
                    </div><div class="col-md-6 icon-box position-relative">
                        <i class="bi bi-people"></i>
                        <h4><a class="stretched-link">Personalized Levels</a></h4>
                        <p>Choose your difficulty based on age and skill to get the perfect challenge tailored to you.</p>
                    </div><div class="col-md-6 icon-box position-relative">
                        <i class="bi bi-lightbulb"></i>
                        <h4><a class="stretched-link">Creative Thinking</a></h4>
                        <p>Explore lateral thinking tasks and fun brain exercises to enhance mental flexibility.</p>
                    </div></div>
            </div>

        </div>

    </div>

</section><section id="services" class="services section">

    <div class="container section-title" data-aos="fade-up">
        <h2>Services</h2>
        <p>Explore the mental fitness tools Smart Mind provides to sharpen your intellect.</p>
    </div><div class="container">

        <div class="row gy-4">

            <div class="col-xl-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
                <div class="service-item position-relative">
                <a href="gamesPro.php"> <i class="bi bi-controller"></i>
                    <h4 class="text-primary fw-bold">Games</h4>
                    <p>Engaging brain games that enhance memory, speed, flexibility, and problem-solving skills.</p></a>
                </div>
            </div><div class="col-xl-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="200">
                <div class="service-item position-relative">
                    <i class="bi bi-bar-chart-steps"></i>
                    <h4 class="text-primary fw-bold">IQ Test</h4>
                    <p>Adaptive IQ assessments to evaluate your logical reasoning and cognitive strengths.</p>
                </div>
            </div><div class="col-xl-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="300">
                <div class="service-item position-relative">
                    <i class="bi bi-lightbulb"></i>
                    <h4 class="text-primary fw-bold">Logic Trainer Questions</h4>
                    <p>Challenging logic-based questions to build analytical thinking and deductive reasoning.</p>
                </div>
            </div></div>

    </div>

</section><section id="why-smartmind" class="why-smartmind section">

    <div class="container section-title" data-aos="fade-up">
        <h2>Why Smart Mind?</h2>
        <p>Discover what makes Smart Mind your ultimate destination for cognitive development and mental training.</p>
    </div><div class="container">
        <div class="row gy-4">

            <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
                <div class="icon-box position-relative">
                    <i class="bi bi-award"></i>
                    <h4>Scientifically Designed</h4>
                    <p>All our challenges are backed by cognitive science to ensure you get real mental benefits while having fun.</p>
                </div>
            </div>

            <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
                <div class="icon-box position-relative">
                    <i class="bi bi-graph-up-arrow"></i>
                    <h4>Track Your Progress</h4>
                    <p>Monitor how your logic, memory, and IQ evolve over time with detailed performance reports.</p>
                </div>
            </div>

            <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box position-relative">
                    <i class="bi bi-emoji-smile"></i>
                    <h4>Fun & Engaging</h4>
                    <p>Smart Mind combines learning and entertainment, making your brain workouts enjoyable and rewarding.</p>
                </div>
            </div>

        </div>
    </div>

</section><section id="our-vision" class="our-vision section">

    <div class="container section-title" data-aos="fade-up">
        <h2>Our Vision</h2>
        <p>Empowering minds of all ages through technology-driven learning and mental growth.</p>
    </div><div class="container">
        <div class="row gy-4">

            <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
                <div class="icon-box position-relative">
                    <i class="bi bi-lightbulb"></i>
                    <h4>Innovation in Education</h4>
                    <p>We aim to revolutionize how people approach cognitive development through innovative tech and gamification.</p>
                </div>
            </div>

            <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
                <div class="icon-box position-relative">
                    <i class="bi bi-people"></i>
                    <h4>Inclusive for All Ages</h4>
                    <p>From children to adults, our platform provides tailored challenges to meet every age group’s mental needs.</p>
                </div>
            </div>

            <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box position-relative">
                    <i class="bi bi-shield-check"></i>
                    <h4>Safe and Trusted</h4>
                    <p>We prioritize user safety, privacy, and provide a secure environment for continuous learning and improvement.</p>
                </div>
            </div>

        </div>
    </div>

</section><section id="contact" class="contact section bg-light py-5">
    <div class="container section-title text-center" data-aos="fade-up">
        <h2>Contact Us</h2>
        <p>If you have any inquiries or need further assistance, feel free to reach out. We are here to help you!</p>
    </div>

    <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">

            <div class="col-lg-5">
                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                    <i class="bi bi-geo-alt flex-shrink-0"></i>
                    <div>
                        <h3>Our Address</h3>
                        <p>Saida-Lebanon</p>
                    </div>
                </div>

                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                    <i class="bi bi-telephone flex-shrink-0"></i>
                    <div>
                        <h3>Call Us</h3>
                        <p>+961 70 976 927</p>
                    </div>
                </div>

                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                    <i class="bi bi-envelope flex-shrink-0"></i>
                    <div>
                        <h3>Email Us</h3>
                        <p>contact@logicwebsite.com</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <form id="contactForm" method="post" class="form-contact" data-aos="fade-up" data-aos-delay="500">
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                        </div>
                        <div class="col-md-12">
                            <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                        </div>
                        <div class="col-md-12">
                            <textarea name="message" class="form-control" rows="6" placeholder="Message" required></textarea>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

<!-- Rate Us Section -->
<section id="rate-us" class="rate-us section py-5" style="background:#f8f9fa;">
  <div class="container" data-aos="fade-up" style="max-width: 400px; text-align: center; margin: auto;">
    <div class="section-title">
      <h2>Rate Us</h2>
      <p>We would love to hear your feedback. Please rate your experience.</p>
    </div>

    <form id="ratingForm" action="feedbacks.php" method="POST" onsubmit="return validateRating()">
      <div class="star-rating" style="font-size: 3rem; color: #ccc; cursor: pointer; user-select: none; margin-bottom: 1rem;">
        <i class="bi bi-star" data-value="1"></i>
        <i class="bi bi-star" data-value="2"></i>
        <i class="bi bi-star" data-value="3"></i>
        <i class="bi bi-star" data-value="4"></i>
        <i class="bi bi-star" data-value="5"></i>
      </div>

      <textarea name="comment" id="comment" rows="4" placeholder="Write your feedback here (optional)" 
        style="width: 100%; padding: 0.5rem; border: 1px solid #ced4da; border-radius: 0.25rem; resize: vertical; font-size: 1rem; margin-bottom: 1rem;"></textarea>

      <input type="hidden" name="rating" id="rating" value="0" />
      
      <button type="submit" class="btn btn-primary" disabled id="submitRatingBtn" style="width: 100%;">Submit Rating</button>
    </form>
  </div>
</section>

<script>
  // Simulate user login status - set this dynamically in your real app
  const isUserLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

  const stars = document.querySelectorAll('#rate-us .star-rating i');
  const ratingInput = document.getElementById('rating');
  const submitBtn = document.getElementById('submitRatingBtn');

  let selectedRating = 0;

  stars.forEach(star => {
    star.addEventListener('mouseover', () => {
      highlightStars(star.dataset.value);
    });

    star.addEventListener('mouseout', () => {
      highlightStars(selectedRating);
    });

    star.addEventListener('click', () => {
      selectedRating = star.dataset.value;
      ratingInput.value = selectedRating;
      highlightStars(selectedRating);
      submitBtn.disabled = false;
    });
  });

  function highlightStars(rating) {
    stars.forEach(star => {
      if (star.dataset.value <= rating) {
        star.classList.add('text-warning');
        star.classList.remove('text-muted');
        star.classList.remove('bi-star');
        star.classList.add('bi-star-fill');
      } else {
        star.classList.remove('text-warning');
        star.classList.add('text-muted');
        star.classList.add('bi-star');
        star.classList.remove('bi-star-fill');
      }
    });
  }

  function validateRating() {
    if (!isUserLoggedIn) {
      alert('You must be logged in to submit a rating.');
      return false;
    }
    if (ratingInput.value < 1 || ratingInput.value > 5) {
      alert('Please select a rating before submitting.');
      return false;
    }
    return true;
  }

  // Initialize stars on page load
  highlightStars(0);
</script>


<div id="successModal" class="modal-blur-overlay d-none">
    <div class="modal-box">
        <p>Message sent successfully!</p>
        <button id="closeModalBtn">OK</button>
    </div>
</div>

<style>
    /* Sidebar Styles (modified) */
    .sidebar {
        position: fixed;
        top: 0;
        right: -350px;
        width: 350px;
        height: 100%;
        background-color: #f8f9fa;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease-in-out;
        z-index: 1000;
        padding: 20px;
        overflow-y: auto;
    }

    .sidebar.open {
        right: 0;
    }

    .sidebar h2 {
        margin-bottom: 15px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
    }

    .sidebar p {
        margin-bottom: 10px;
    }

    .sidebar .btn-edit {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-bottom: 15px;
    }

    .sidebar .btn-edit:hover {
        background-color: #0056b3;
    }

    .profile-icon-container {
        cursor: pointer;
    }

    .sidebar .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        font-size: 1.5em;
        color: #555;
        border: none;
        background: none;
        padding: 0;
    }

    .sidebar .close-btn:hover {
        color: #000;
    }

    .edit-profile-form {
        display: none; /* Initially hidden */
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="date"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .btn-save {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-right: 10px;
    }

    .btn-save:hover {
        background-color: #1e7e34;
    }

    .btn-cancel {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-cancel:hover {
        background-color: #c82333;
    }

    .error-message {
        color: red;
        margin-top: 10px;
    }

    .success-message {
        color: green;
        margin-top: 10px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.profile-icon-container').click(function() {
            $('.sidebar').addClass('open');
        });

        $('.sidebar .close-btn').click(function() {
            $('.sidebar').removeClass('open');
            $('#profileInfo').show();
            $('#editProfileForm').hide();
            $('#update-messages').empty(); // Clear any previous messages
        });

        $('.sidebar .btn-edit').click(function() {
            $('#profileInfo').hide();
            $('#editProfileForm').show();
        });

        $('.btn-cancel').click(function() {
            $('#profileInfo').show();
            $('#editProfileForm').hide();
            $('#update-messages').empty(); // Clear any messages on cancel
        });

        $('#updateProfileForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: 'update_profile.php',
                data: formData,
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    $('#update-messages').empty();
                    if (response.success) {
                        $('#update-messages').html('<p class="success-message">' + response.message + '</p>');
                        // Update displayed info in the sidebar (optional, can reload too)
                        $('#profileInfo p:nth-child(1) strong').next().text(response.name);
                        $('#profileInfo p:nth-child(2) strong').next().text(response.email);
                        $('#profileInfo p:nth-child(3) strong').next().text(response.dob);
                        // Optionally hide the edit form after successful save
                        setTimeout(function() {
                            $('#profileInfo').show();
                            $('#editProfileForm').hide();
                        }, 1500);
                    } else {
                        $('#update-messages').html('<p class="error-message">' + response.message + '</p>');
                    }
                },
                error: function() {
                    $('#update-messages').html('<p class="error-message">An error occurred. Please try again.</p>');
                }
            });
        });

        $('#contactForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: 'contactForm.php',
                data: $(this).serialize(),
                success: function(response) {
                    $('#contactForm')[0].reset();
                    $('#successModal').removeClass('d-none');
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        });

        $('#closeModalBtn').click(function() {
            $('#successModal').addClass('d-none');
        });
    });
</script>
</div>
    </div>
    </div>
</section>

</main>

<footer id="footer" class="footer bg-white text-dark py-4">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-3">
                <p>© <span>2025</span> <strong class="px-1 sitename">SmartMind</strong> | All Rights Reserved</p>
            </div>
            <div class="col-12 text-center mb-3">
                <a href="/about-us" class="text-dark mx-3">About Us</a>
                <a href="/privacy-policy" class="text-dark mx-3">Privacy Policy</a>
                <a href="/terms-of-service" class="text-dark mx-3">Terms of Service</a>
                <a href="/contact-us" class="text-dark mx-3">Contact Us</a>
            </div>
        </div>
    </div>
</footer>

<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<div id="preloader"></div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

<script src="assets/js/main.js"></script>

</body>

</html>