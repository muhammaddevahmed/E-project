<?php
include 'php/db_connection.php'; // Include your database connection file
include("components/header.php");

// Check if user is logged in and fetch their details
$defaultName = '';
$defaultEmail = '';
if (isset($_SESSION['user_id'])) {
    try {
        $query = "SELECT full_name, email FROM users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $defaultName = $user['full_name'] ?? '';
        $defaultEmail = $user['email'] ?? '';
    } catch (PDOException $e) {
        error_log("Error fetching user data: " . $e->getMessage());
        // Continue with empty defaults if database query fails
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $feedbackText = htmlspecialchars($_POST['feedback_text']);
    $rating = intval($_POST['rating']);

    // Check if required fields are filled
    if (empty($name) || empty($email) || empty($feedbackText) || empty($rating)) {
        echo "<script>alert('Please fill all required fields');</script>";
    } else {
        try {
            // Retrieve user_id from the session (if logged in)
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

            // Insert the feedback into the database
            $sql = "INSERT INTO feedback (name, email, user_id, feedback_text, rating, submitted_at)
                    VALUES (:name, :email, :user_id, :feedback_text, :rating, NOW())";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':feedback_text', $feedbackText);
            $stmt->bindParam(':rating', $rating);
            
            if ($stmt->execute()) {
                echo "<script>alert('Thank you for your feedback!'); window.location.href='index.php';</script>";
                exit;
            } else {
                throw new Exception("Failed to submit feedback");
            }
        } catch (PDOException $e) {
            echo "<script>alert('Please Login First!'); window.location.href='login.php';</script>";
            error_log("Database error: " . $e->getMessage());
        }
    }
}
?>

<style>
.star-rating {
  display: inline-block;
  font-size: 0;
  unicode-bidi: bidi-override;
  direction: ltr;
}

.star-rating span {
  font-size: 30px;
  color: #ccc;
  cursor: pointer;
  display: inline-block;
  margin: 0 5px;
  transition: color 0.2s;
}

.star-rating span.selected {
  color: #ffcc00;
}

/* Chatbot Iframe Styles */
.chatbot-container {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 1000;
}

.chatbot-button {
  background-color: #7fad39;
  color: #fff;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  transition: background-color 0.3s;
}

.chatbot-button:hover {
  background-color: #e6b800;
}

.chatbot-button i {
  font-size: 24px;
}

.chatbot-iframe-container {
  display: none;
  width: 350px;
  height: 450px;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  margin-bottom: 10px;
}

.chatbot-iframe-container.open {
  display: block;
}

.chatbot-header {
  background-color: #7fad39;
  color: #fff;
  padding: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.chatbot-header h4 {
  margin: 0;
  font-size: 18px;
}

.chatbot-close {
  cursor: pointer;
  font-size: 20px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .chatbot-iframe-container {
    width: 300px;
    height: 400px;
  }
}
</style>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg"
  data-setbg="https://i.pinimg.com/736x/72/e6/21/72e62198095a1c36038869ddf05481f7.jpg">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <div class="breadcrumb__text">
          <h2>Contact Us</h2>
          <div class="breadcrumb__option">
            <a href="./index.php">Home</a>
            <span>Contact Us</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Contact Section Begin -->
<section class="contact spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6 text-center">
        <div class="contact__widget">
          <span class="icon_phone"></span>
          <h4>Phone</h4>
          <p>+92 3442681140</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 text-center">
        <div class="contact__widget">
          <span class="icon_pin_alt"></span>
          <h4>Address</h4>
          <p>Aptech: Shahra-e-Faisal Karachi</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 text-center">
        <div class="contact__widget">
          <span class="icon_clock_alt"></span>
          <h4>Open time</h4>
          <p>10:00 am to 23:00 pm</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 text-center">
        <div class="contact__widget">
          <span class="icon_mail_alt"></span>
          <h4>Email</h4>
          <p>thecraftycorner@gmail.com</p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Contact Section End -->

<!-- Map Begin -->
<div class="map">
  <iframe
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3620.0170064265662!2d67.07181781127083!3d24.86326874505247!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33ea3db108f41%3A0x42acc4507358b160!2sAptech%20Learning%2C%20Shahrah%20e%20Faisal%20Center!5e0!3m2!1sen!2s!4v1742819945063!5m2!1sen!2s"
    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
    referrerpolicy="no-referrer-when-downgrade"></iframe>
  <div class="map-inside">
    <i class="icon_pin"></i>
    <div class="inside-widget">
      <h4>Karachi,Pakistan</h4>
      <ul>
        <li>Phone: +92 3442681140</li>
        <li>Aptech: Shahra-e-Faisal</li>
      </ul>
    </div>
  </div>
</div>
<!-- Map End -->

<!-- Contact Form Begin -->
<div class="contact-form spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="contact__form__title">
          <h2>Give us Feedback for Improvement</h2>
        </div>
      </div>
    </div>
    <?php if (isset($successMessage)): ?>
    <div class="alert alert-success text-center"><?php echo $successMessage; ?></div>
    <?php endif; ?>
    <?php if (isset($errorMessage)): ?>
    <div class="alert alert-danger text-center"><?php echo $errorMessage; ?></div>
    <?php endif; ?>
    <form action="" method="POST">
      <div class="row">
        <div class="col-lg-6 col-md-6">
          <input type="text" name="name" placeholder="Your name" value="<?php echo htmlspecialchars($defaultName); ?>"
            required>
        </div>
        <div class="col-lg-6 col-md-6">
          <input type="email" name="email" placeholder="Your Email"
            value="<?php echo htmlspecialchars($defaultEmail); ?>" required>
        </div>
        <div class="col-lg-12 text-center">
          <textarea name="feedback_text" placeholder="Your message" required></textarea>
        </div>
        <div class="col-lg-12 text-center">
          <label for="rating">Rating:</label>
          <div class="star-rating">
            <span data-value="1">★</span>
            <span data-value="2">★</span>
            <span data-value="3">★</span>
            <span data-value="4">★</span>
            <span data-value="5">★</span>
          </div>
          <input type="hidden" name="rating" id="rating" required>
        </div>
        <div class="col-lg-12 text-center">
          <button type="submit" class="site-btn">SEND MESSAGE</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Contact Form End -->

<!-- Chatbot Iframe Begin -->
<div class="chatbot-container">
  <div class="chatbot-button" id="chatbot-toggle">
    <i class="fa fa-comment"></i>
  </div>
  <div class="chatbot-iframe-container" id="chatbot-iframe-container">
    <div class="chatbot-header">
      <h4>Chat with Us</h4>
      <span class="chatbot-close" id="chatbot-close">×</span>
    </div>
    <iframe src="https://www.chatbase.co/chatbot-iframe/1UT8fd5KZpZF9IPlSYquk" width="100%"
      style="height: calc(100% - 50px); min-height: 400px" frameborder="0"></iframe>
  </div>
</div>
<!-- Chatbot Iframe End -->

<?php
include("components/footer.php");
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const stars = document.querySelectorAll('.star-rating span');
  const ratingInput = document.getElementById('rating');

  stars.forEach((star, index) => {
    star.addEventListener('click', function() {
      const value = this.getAttribute('data-value');
      ratingInput.value = value; // Update the hidden input value

      // Remove 'selected' class from all stars
      stars.forEach(s => s.classList.remove('selected'));

      // Add 'selected' class to clicked star and previous ones
      for (let i = 0; i < value; i++) {
        stars[i].classList.add('selected');
      }
    });
  });

  // Chatbot iframe toggle functionality
  const chatbotToggle = document.getElementById('chatbot-toggle');
  const chatbotIframeContainer = document.getElementById('chatbot-iframe-container');
  const chatbotClose = document.getElementById('chatbot-close');

  chatbotToggle.addEventListener('click', () => {
    chatbotIframeContainer.classList.toggle('open');
  });

  chatbotClose.addEventListener('click', () => {
    chatbotIframeContainer.classList.remove('open');
  });
});
</script>