<?php
include("components/header.php");

// Fetch all feedback with user details if available
try {
    $query = "SELECT f.*, u.username 
              FROM feedback f
              LEFT JOIN users u ON f.user_id = u.user_id
              ORDER BY f.submitted_at DESC";
    $stmt = $pdo->query($query);
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Feedback</title>
  <style>
  :root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --accent-color: #4895ef;
    --light-color: #f8f9fa;
    --dark-color: #212529;
    --success-color: #4cc9f0;
    --warning-color: #f8961e;
    --danger-color: #f72585;
  }

  body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f7ff;
    color: var(--dark-color);
    line-height: 1.6;
  }

  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
  }

  .page-header {
    text-align: center;
    margin-bottom: 3rem;
  }

  .page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 1rem;
  }

  .page-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    max-width: 700px;
    margin: 0 auto;
  }

  .feedback-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
  }

  .feedback-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    padding: 2rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
  }

  .feedback-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  }

  .feedback-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
  }

  .user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    margin-right: 1rem;
  }

  .user-info {
    flex: 1;
  }

  .user-name {
    font-weight: 600;
    margin-bottom: 0.2rem;
    color: var(--dark-color);
  }

  .user-email {
    font-size: 0.9rem;
    color: #6c757d;
  }

  .feedback-content {
    margin-bottom: 1.5rem;
    color: #495057;
    line-height: 1.7;
  }

  .feedback-rating {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
  }

  .stars {
    color: #ffc107;
    font-size: 1.2rem;
    letter-spacing: 2px;
  }

  .rating-value {
    margin-left: 0.5rem;
    font-weight: 600;
    color: var(--dark-color);
  }

  .feedback-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
    color: #6c757d;
    border-top: 1px solid #f1f1f1;
    padding-top: 1rem;
  }

  .feedback-date {
    display: flex;
    align-items: center;
  }

  .no-feedback {
    text-align: center;
    padding: 4rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    grid-column: 1 / -1;
  }

  .no-feedback-icon {
    font-size: 3rem;
    color: #adb5bd;
    margin-bottom: 1rem;
  }

  .no-feedback-text {
    font-size: 1.1rem;
    color: #6c757d;
  }

  @media (max-width: 768px) {
    .feedback-grid {
      grid-template-columns: 1fr;
    }

    .container {
      padding: 1.5rem;
    }

    .page-title {
      font-size: 2rem;
    }
  }
  </style>
</head>

<body>
  <div class="container">
    <div class="page-header">
      <h1 class="page-title">Customer Feedback</h1>
      <p class="page-subtitle">What our valued customers say about their experience with us</p>
    </div>

    <div class="feedback-grid">
      <?php if (count($feedbacks) > 0): ?>
      <?php foreach ($feedbacks as $feedback): ?>
      <div class="feedback-card">
        <div class="feedback-header">
          <div class="user-avatar">
            <?php echo strtoupper(substr($feedback['name'] ?: ($feedback['username'] ?? 'U'), 0, 1)); ?>
          </div>
          <div class="user-info">
            <div class="user-name">
              <?php echo htmlspecialchars($feedback['name'] ?: ($feedback['username'] ?? 'Anonymous')); ?></div>
            <div class="user-email"><?php echo htmlspecialchars($feedback['email'] ?? 'No email provided'); ?></div>
          </div>
        </div>

        <?php if ($feedback['rating'] !== null): ?>
        <div class="feedback-rating">
          <div class="stars">
            <?php 
                                $rating = (int)$feedback['rating'];
                                $fullStars = str_repeat('★', $rating);
                                $emptyStars = str_repeat('☆', 5 - $rating);
                                echo $fullStars . $emptyStars;
                                ?>
          </div>
          <span class="rating-value"><?php echo $rating; ?>/5</span>
        </div>
        <?php endif; ?>

        <div class="feedback-content">
          <?php echo nl2br(htmlspecialchars($feedback['feedback_text'])); ?>
        </div>

        <div class="feedback-meta">
          <div class="feedback-date">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px;">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <?php echo date('F j, Y', strtotime($feedback['submitted_at'])); ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php else: ?>
      <div class="no-feedback">
        <div class="no-feedback-icon">📭</div>
        <h3>No Feedback Yet</h3>
        <p class="no-feedback-text">We haven't received any feedback from customers yet.</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>

<?php include("components/footer.php"); ?>