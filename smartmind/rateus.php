<section id="feedback" class="feedback section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Give Your Feedback</h2>
    <p>We value your thoughts. Rate your experience with SmartMind Academy!</p>
  </div>

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <form action="submit_feedback.php" method="post">
      <div class="mb-3">
        <label for="name" class="form-label">Your Name (optional)</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
      </div>

      <div class="mb-3">
        <label class="form-label">Rating</label>
        <div class="star-rating">
          <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 stars">&#9733;</label>
          <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars">&#9733;</label>
          <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars">&#9733;</label>
          <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars">&#9733;</label>
          <input type="radio" id="star1" name="rating" value="1" required><label for="star1" title="1 star">&#9733;</label>
        </div>
      </div>

      <div class="mb-3">
        <label for="comment" class="form-label">Your Feedback</label>
        <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Submit Feedback</button>
    </form>
  </div>

  <style>
    .star-rating {
      direction: rtl;
      font-size: 2em;
      display: flex;
      gap: 5px;
      justify-content: flex-start;
    }

    .star-rating input {
      display: none;
    }

    .star-rating label {
      color: #ccc;
      cursor: pointer;
    }

    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
      color: #f5b301;
    }
  </style>
</section>