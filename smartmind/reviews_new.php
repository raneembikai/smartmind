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
  <script id="messenger-widget-b" src="https://cdn.botpenguin.com/website-bot.js" defer>6814def45987761472635840,6814de68cebc80433966ecd0</script>
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
    .blur-background {
      filter: blur(5px);
      pointer-events: none;
    }
    .login-modal {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      z-index: 1000;
    }
    .modal-backdrop {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 999;
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

    <div class="row text-center" id="reviews-container">
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

            <div class="d-flex justify-content-center gap-4 mt-3">
              <button class="btn btn-sm btn-outline-success" onclick="handleLikeDislike('like', this)">
                👍 Like
              </button>
              <button class="btn btn-sm btn-outline-danger" onclick="handleLikeDislike('dislike', this)">
                👎 Dislike
              </button>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>

    <!-- Back to Main Page Button -->
    <div class="text-center mt-4">
      <a href="index.php" class="btn btn-primary">Back to Main Page</a>
    </div>
  </div>
</section>

<!-- Login Modal -->
<div class="modal-backdrop" id="modalBackdrop"></div>
<div class="login-modal" id="loginModal">
  <h4 class="mb-3">Login Required</h4>
  <p>Please login to like or dislike reviews.</p>
  <div class="d-flex justify-content-center gap-2">
    <a href="login.php" class="btn btn-primary">Login</a>
    <button class="btn btn-secondary" onclick="closeLoginModal()">Close</button>
  </div>
</div>

<script>
  function handleLikeDislike(action, button) {
    <?php if (isset($_SESSION['user_id'])): ?>
      // User is logged in, handle like/dislike
      const isLiked = button.classList.contains('btn-success');
      const isDisliked = button.classList.contains('btn-danger');
      
      if (action === 'like') {
        if (isLiked) {
          button.classList.remove('btn-success');
          button.classList.add('btn-outline-success');
        } else {
          button.classList.remove('btn-outline-success');
          button.classList.add('btn-success');
          // Remove dislike if it was active
          const dislikeBtn = button.parentElement.querySelector('.btn-outline-danger');
          if (dislikeBtn.classList.contains('btn-danger')) {
            dislikeBtn.classList.remove('btn-danger');
            dislikeBtn.classList.add('btn-outline-danger');
          }
        }
      } else {
        if (isDisliked) {
          button.classList.remove('btn-danger');
          button.classList.add('btn-outline-danger');
        } else {
          button.classList.remove('btn-outline-danger');
          button.classList.add('btn-danger');
          // Remove like if it was active
          const likeBtn = button.parentElement.querySelector('.btn-outline-success');
          if (likeBtn.classList.contains('btn-success')) {
            likeBtn.classList.remove('btn-success');
            likeBtn.classList.add('btn-outline-success');
          }
        }
      }
    <?php else: ?>
      // User is not logged in, show modal
      showLoginModal();
    <?php endif; ?>
  }

  function showLoginModal() {
    document.getElementById('modalBackdrop').style.display = 'block';
    document.getElementById('loginModal').style.display = 'block';
    document.getElementById('reviews-container').classList.add('blur-background');
  }

  function closeLoginModal() {
    document.getElementById('modalBackdrop').style.display = 'none';
    document.getElementById('loginModal').style.display = 'none';
    document.getElementById('reviews-container').classList.remove('blur-background');
  }
</script>

</body>
</html>

<?php $conn->close(); ?> 