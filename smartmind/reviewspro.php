<?php
session_start();
$conn = new mysqli("localhost", "root", "", "smartmind_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, message FROM feedback_reviews ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reviews</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2d3a4da3b.js" crossorigin="anonymous"></script>
  <style>
    body {
      background-color: rgb(244, 251, 252);  
    }
    .card {
      border-radius: 0.7rem;
      transition: transform 0.3s ease-in-out;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card-body {
      background-color: #fff;
      border-radius: 0.7rem;
    }
    .text-primary {
      color: #5bc0de !important; 
    }
    .btn-primary {
      background-color: #6ba7b7;
      border-color: #6ba7b7;
    }
    .btn-primary:hover {
      background-color: #7db9d9;
      border-color: #7db9d9;
    }
  </style>
</head>
<body>

<section style="color: #000;">
  <div class="container py-5">
    <div class="row d-flex justify-content-center">
      <div class="col-md-10 col-xl-8 text-center">
        <h3 class="fw-bold mb-4">Testimonials</h3>
        <p class="mb-4 pb-2 mb-md-5 pb-md-0">
          Feedback from our valued users.
        </p>
      </div>
    </div>

    <div class="row text-center">
      <?php while ($row = $result->fetch_assoc()): 
        $name = htmlspecialchars($row['name']);
        $message = htmlspecialchars($row['message']);
        $maskedName = substr($name, 0, 2) . str_repeat("*", max(strlen($name) - 2, 0));
      ?>
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body py-4 px-3">
            <h5 class="fw-bold text-primary mb-2"><?php echo $maskedName; ?></h5>
            <p class="text-muted">
              <i class="fas fa-quote-left pe-2 text-secondary"></i><?php echo $message; ?>
            </p>

            <?php if (!isset($_SESSION['user_id'])): ?>
              <div class="mt-2">
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>

    
    <div class="text-center mt-4">
      <a href="profile.php" class="btn btn-primary">Back to Main Page</a>
    </div>
  </div>
</section>

</body>
</html>

<?php $conn->close(); ?>
