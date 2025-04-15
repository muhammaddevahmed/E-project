<?php
include("components/header.php");
?>

<style>
/* General Styling */
.faqs {
  padding: 100px 0;
  background-color: #f9f9f9;
  position: relative;
  overflow: hidden;
  animation: fadeIn 0.8s ease-in;
}

.faqs::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(255, 255, 255, 0.1) 100%);
  z-index: -1;
}

.faqs__title h2 {
  font-size: 38px;
  font-weight: 800;
  color: #333;
  text-align: center;
  margin-bottom: 50px;
  letter-spacing: 1px;
  text-transform: uppercase;
  position: relative;
  padding-bottom: 15px;
}

.faqs__title h2::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 4px;
  background-color: #28a745;
  border-radius: 2px;
}

/* FAQ Category */
.faqs__category {
  margin-bottom: 40px;
}

.faqs__category h3 {
  font-size: 24px;
  font-weight: 700;
  color: #333;
  margin-bottom: 25px;
  padding-left: 15px;
  border-left: 4px solid #28a745;
}

/* FAQ Items */
.faqs__item {
  margin-bottom: 15px;
  border-radius: 10px;
  background-color: #fff;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.faqs__item:hover {
  transform: scale(1.02);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.faqs__item details {
  cursor: pointer;
}

.faqs__item summary {
  font-size: 19px;
  font-weight: 600;
  color: #333;
  padding: 20px 25px;
  background-color: #f8f9fa;
  border-bottom: 1px solid #ddd;
  display: flex;
  align-items: center;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.faqs__item summary:hover {
  background-color: #f1f1f1;
  color: #28a745;
}

.faqs__item summary::-webkit-details-marker {
  display: none;
}

.faqs__item summary::before {
  font-family: 'FontAwesome';
  font-size: 22px;
  color: #28a745;
  margin-right: 15px;
  transition: transform 0.3s ease, color 0.3s ease;
}

.faqs__item details[open] summary::before {
  transform: rotate(45deg);
}

.faqs__item p {
  font-size: 16px;
  color: #555;
  line-height: 1.8;
  padding: 25px;
  margin: 0;
  background-color: #fff;
  animation: slideDown 0.3s ease-in-out;
}

/* Breadcrumb Section */
.breadcrumb-section {
  padding: 80px 0;
  background-size: cover;
  background-position: center;
  position: relative;
  z-index: 1;
}

.breadcrumb-section::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, rgba(40, 167, 69, 0.3), rgba(0, 0, 0, 0.5));
  z-index: -1;
}

.breadcrumb__text h2 {
  font-size: 40px;
  font-weight: 800;
  color: #fff;
  margin-bottom: 20px;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  animation: pulseText 2s infinite;
}

.breadcrumb__option a,
.breadcrumb__option span {
  font-size: 17px;
  color: #fff;
  font-weight: 500;
  transition: color 0.3s ease, transform 0.3s ease;
}

.breadcrumb__option a:hover {
  color: #28a745;
  transform: translateX(5px);
}

/* Responsive Design */
@media (max-width: 768px) {
  .faqs {
    padding: 70px 0;
  }

  .faqs__title h2 {
    font-size: 32px;
    margin-bottom: 35px;
  }

  .faqs__category h3 {
    font-size: 20px;
    margin-bottom: 20px;
  }

  .breadcrumb__text h2 {
    font-size: 32px;
  }

  .faqs__item summary {
    font-size: 17px;
    padding: 15px 20px;
  }

  .faqs__item p {
    font-size: 15px;
    padding: 20px;
  }

  .faqs__item summary::before {
    font-size: 20px;
    margin-right: 12px;
  }
}

@media (max-width: 576px) {
  .faqs__title h2::after {
    width: 60px;
  }

  .faqs__item {
    margin-bottom: 10px;
  }
}

/* Animations */
@keyframes fadeIn {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }

  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideDown {
  0% {
    opacity: 0;
    transform: translateY(-10px);
  }

  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes pulseText {
  0% {
    transform: scale(1);
  }

  50% {
    transform: scale(1.02);
  }

  100% {
    transform: scale(1);
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

<!-- FAQs Section Begin -->
<section class="faqs">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="faqs__title">
          <h2>Your Questions, Answered</h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-10 offset-lg-1">
        <!-- General Questions -->
        <div class="faqs__category">
          <h3>General</h3>
          <div class="faqs__item">
            <details>
              <summary style="content: '\f1fc';">What types of arts and stationery products do you offer?</summary>
              <p>At The Crafty Corner, we provide a vibrant selection of sketchbooks, watercolors, acrylics, brushes,
                calligraphy pens, markers, craft kits, and more, perfect for artists, students, and hobbyists.</p>
            </details>
          </div>
          <div class="faqs__item">
            <details>
              <summary style="content: '\f044';">Are your products suitable for beginners?</summary>
              <p>Yes! Our beginner-friendly kits come with guides to spark creativity. Check out our "Beginner’s Choice"
                products for curated supplies to kickstart your artistic journey.</p>
            </details>
          </div>
          <div class="faqs__item">
            <details>
              <summary style="content: '\f015';">Do you have a physical store?</summary>
              <p>We’re an online-only shop based in Karachi at Aptech, Shahra-e-Faisal. Visit our <a
                  href="./contact.php">Contact page</a> to connect with us!</p>
            </details>
          </div>
        </div>
        <!-- Ordering and Shipping -->
        <div class="faqs__category">
          <h3>Ordering & Shipping</h3>
          <div class="faqs__item">
            <details>
              <summary style="content: '\f0d1';">How long does shipping take?</summary>
              <p>Domestic orders (Pakistan) arrive in 3-7 business days, while international orders take 7-14 days.
                Track your package with the link sent via email.</p>
            </details>
          </div>
          <div class="faqs__item">
            <details>
              <summary style="content: '\f217';">Do you offer custom or bulk orders?</summary>
              <p>Absolutely! We create custom art kits and offer bulk discounts for schools or events. Email us at <a
                  href="mailto:thecraftycorner@gmail.com">thecraftycorner@gmail.com</a> to get started.</p>
            </details>
          </div>
        </div>
        <!-- Returns and Care -->
        <div class="faqs__category">
          <h3>Returns & Product Care</h3>
          <div class="faqs__item">
            <details>
              <summary style="content: '\f112';">What is your return policy?</summary>
              <p>Unused, undamaged items can be returned within 14 days. Start the process on our <a
                  href="./returns.php">Returns page</a>. Refunds are issued within 5-7 days of receipt.</p>
            </details>
          </div>
          <div class="faqs__item">
            <details>
              <summary style="content: '\f0c4';">How should I care for my art supplies?</summary>
              <p>Store paints and markers in a cool, dry place. Clean brushes with water (watercolors) or mineral
                spirits (oils). Keep sketchbooks away from humidity.</p>
            </details>
          </div>
        </div>
        <!-- Support -->
        <div class="faqs__category">
          <h3>Support</h3>
          <div class="faqs__item">
            <details>
              <summary style="content: '\f095';">How can I contact customer support?</summary>
              <p>Reach us at <a href="mailto:thecraftycorner@gmail.com">thecraftycorner@gmail.com</a>, call +92
                3442681140, or use our <a href="./contact.php">Contact page</a>. We reply within 24-48 hours.</p>
            </details>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- FAQs Section End -->

<?php
include("components/footer.php");
?>