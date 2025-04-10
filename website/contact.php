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
</style>
<?php
include 'php/db_connection.php'; // Include your database connection file
include("components/header.php");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $feedbackText = htmlspecialchars($_POST['feedback_text']);
    $rating = intval($_POST['rating']); // Ensure rating is an integer

    // Retrieve user_id from the session (if logged in)
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Insert the feedback into the database
    $sql = "INSERT INTO feedback (name, email, user_id, feedback_text, rating, submitted_at)
            VALUES (:name, :email, :user_id, :feedback_text, :rating, NOW())";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':feedback_text', $feedbackText);
        $stmt->bindParam(':rating', $rating);

        // Execute the statement
        $stmt->execute();

        // Show a success message
        echo "<script>alert('Thank you for submitting your feedback'); window.location.href='index.php';</script>";
        $successMessage = "Thank you for your feedback!";

    } catch (PDOException $e) {
        // Handle errors
        $errorMessage = "Error: " . $e->getMessage();
    }
}
?>

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
        <li>Aptech: Shahra-e-Faisal </li>
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
          <input type="text" name="name" placeholder="Your name" required>
        </div>
        <div class="col-lg-6 col-md-6">
          <input type="email" name="email" placeholder="Your Email" required>
        </div>
        <div class="col-lg-12 text-center">
          <textarea name="feedback_text" placeholder="Your message" required></textarea>
        </div>
        <div class="col-lg-12 text-center">
          <label for="rating">Rating:</label>
          <div class="star-rating">
            <span data-value="1">&#9733;</span>
            <span data-value="2">&#9733;</span>
            <span data-value="3">&#9733;</span>
            <span data-value="4">&#9733;</span>
            <span data-value="5">&#9733;</span>
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
});
</script>