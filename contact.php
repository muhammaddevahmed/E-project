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
<section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Contact Us</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.html">Home</a>
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
                    <p>+01-3-8888-6868</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <span class="icon_pin_alt"></span>
                    <h4>Address</h4>
                    <p>60-49 Road 11378 New York</p>
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
                    <p>hello@colorlib.com</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->

<!-- Map Begin -->
<div class="map">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d49116.39176087041!2d-86.41867791216099!3d39.69977417971648!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x886ca48c841038a1%3A0x70cfba96bf847f0!2sPlainfield%2C%20IN%2C%20USA!5e0!3m2!1sen!2sbd!4v1586106673811!5m2!1sen!2sbd"
        height="500" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    <div class="map-inside">
        <i class="icon_pin"></i>
        <div class="inside-widget">
            <h4>New York</h4>
            <ul>
                <li>Phone: +12-345-6789</li>
                <li>Add: 16 Creek Ave. Farmingdale, NY</li>
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

document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star-rating span');
    const ratingInput = document.getElementById('rating');

    stars.forEach((star, index) => {
        star.addEventListener('click', function () {
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