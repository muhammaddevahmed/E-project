<?php
include("components/header.php");

// Fetch all reviews
try {
    $query = "SELECT r.*, p.product_name 
              FROM reviews r
              JOIN products p ON r.product_id = p.product_id
              ORDER BY r.created_at DESC";
    $stmt = $pdo->query($query);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>


<style>
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  margin: 0;
  padding: 20px;
  background-color: #f5f7fa;
  color: #333;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}

h1 {

  font-size: 2.5rem;
  font-weight: 700;
  color: #7fad39;
  margin-bottom: 1rem;


  text-align: center;
}

.reviews-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.review-card {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  padding: 20px;
  margin-bottom: 20px;
}

.review-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
  align-items: center;
}

.review-product {
  font-weight: 600;
  color: #3498db;
  margin-bottom: 5px;
}

.review-user {
  font-weight: 600;
  color: #2c3e50;
}

.review-date {
  color: #7f8c8d;
  font-size: 14px;
}

.review-rating {
  color: #f39c12;
  font-weight: bold;
  font-size: 18px;
}

.review-text {
  margin-top: 15px;
  line-height: 1.6;
  color: #34495e;
}

.stars {
  color: #f1c40f;
  font-size: 20px;
}

.no-reviews {
  text-align: center;
  padding: 40px;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  color: #7f8c8d;
}

@media (max-width: 768px) {
  .review-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .review-date {
    margin-top: 5px;
  }
}
</style>

<body>
  <div class="container">
    <h1>Product Reviews</h1>

    <?php if (count($reviews) > 0): ?>
    <div class="reviews-list">
      <?php foreach ($reviews as $review): ?>
      <div class="review-card">
        <div class="review-header">
          <div>
            <div class="review-product"><?php echo htmlspecialchars($review['product_name']); ?></div>
            <div class="review-user"><?php echo htmlspecialchars($review['user_name']); ?></div>
          </div>
          <div class="review-date">
            <?php echo date('M j, Y \a\t g:i a', strtotime($review['created_at'])); ?>
          </div>
        </div>

        <div class="review-rating">
          <?php 
                            // Display star rating
                            $fullStars = floor($review['rating']);
                            $halfStar = ($review['rating'] - $fullStars) >= 0.5;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            
                            for ($i = 0; $i < $fullStars; $i++) {
                                echo '<span class="stars">★</span>';
                            }
                            if ($halfStar) {
                                echo '<span class="stars">★</span>';
                            }
                            for ($i = 0; $i < $emptyStars; $i++) {
                                echo '<span class="stars">☆</span>';
                            }
                            ?>
          (<?php echo htmlspecialchars($review['rating']); ?>/5)
        </div>

        <div class="review-text">
          <?php echo nl2br(htmlspecialchars($review['review_text'])); ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="no-reviews">
      <p>No reviews have been submitted yet.</p>
    </div>
    <?php endif; ?>
  </div>
</body>



<?php include("components/footer.php"); ?>