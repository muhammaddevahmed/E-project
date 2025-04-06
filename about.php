<style>
:root {
  --primary: #ff6b6b;
  --secondary: #5f27cd;
  --accent: #1dd1a1;
  --light: #f9f9f9;
  --dark: #222f3e;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background-color: #fef6f6;
  color: var(--dark);
  overflow-x: hidden;
}

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* Hero Section */
.hero {
  height: 90vh;
  background: linear-gradient(135deg, rgba(255, 107, 107, 0.9), rgba(95, 39, 205, 0.9)),
    url('https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
  background-size: cover;
  background-position: center;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  position: relative;
  clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
  margin-top: 10px
}

.hero-content {
  max-width: 800px;
  padding: 0 20px;
  z-index: 2;
}

.hero h1 {
  font-size: 4.5rem;
  color: white;
  margin-bottom: 1.5rem;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
  animation: fadeInDown 1s ease;
}

.hero p {
  font-size: 1.5rem;
  color: white;
  margin-bottom: 2rem;
  font-weight: 300;
  animation: fadeInUp 1s ease 0.3s both;
}

.btn-hero {
  display: inline-block;
  background: white;
  color: var(--primary);
  padding: 15px 40px;
  border-radius: 50px;
  text-decoration: none;
  font-weight: 600;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  animation: fadeIn 1s ease 0.6s both;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.btn-hero:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
}

/* Floating Elements */
.floating {
  position: absolute;
  animation: float 6s ease-in-out infinite;
}

.floating-1 {
  top: 20%;
  left: 10%;
  width: 80px;
  animation-delay: 0s;
}

.floating-2 {
  top: 60%;
  right: 15%;
  width: 100px;
  animation-delay: 0.5s;
}

.floating-3 {
  bottom: 10%;
  left: 20%;
  width: 60px;
  animation-delay: 1s;
}

/* Story Section */
.story {
  padding: 100px 0;
  position: relative;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.section-title {
  text-align: center;
  margin-bottom: 60px;
}

.section-title h2 {
  font-size: 3rem;
  color: var(--primary);
  position: relative;
  display: inline-block;
  margin-bottom: 20px;
}

.section-title h2::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 4px;
  background: var(--accent);
  border-radius: 2px;
}

.story-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 40px;
  align-items: center;
}

.story-image {
  position: relative;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  transform: rotate(-5deg);
  transition: transform 0.5s ease;
}

.story-image:hover {
  transform: rotate(0deg) scale(1.05);
}

.story-image img {
  width: 100%;
  height: auto;
  display: block;
}

.story-image::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(45deg, var(--primary), var(--secondary));
  opacity: 0.1;
}

.story-content {
  padding: 20px;
}

.story-content h3 {
  font-size: 2rem;
  margin-bottom: 20px;
  color: var(--secondary);
}

.story-content p {
  margin-bottom: 15px;
  line-height: 1.8;
  font-size: 1.1rem;
}

/* Features Section */
.features {
  padding: 100px 0;
  background: linear-gradient(135deg, #f9f9f9, #ffffff);
  clip-path: polygon(0 10%, 100% 0, 100% 90%, 0 100%);
  margin-top: -50px;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
  margin-top: 50px;
}

.feature-card {
  background: white;
  border-radius: 15px;
  padding: 40px 30px;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  z-index: 1;
}

.feature-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

.feature-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 5px;
  background: linear-gradient(90deg, var(--primary), var(--secondary));
}

.feature-icon {
  font-size: 3.5rem;
  margin-bottom: 20px;
  background: linear-gradient(45deg, var(--primary), var(--secondary));
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

.feature-card h3 {
  font-size: 1.5rem;
  margin-bottom: 15px;
  color: var(--dark);
}

.feature-card p {
  color: #666;
  line-height: 1.7;
}

/* Testimonials */
.testimonials {
  padding: 100px 0;
  background: url('https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center/cover;
  position: relative;
  color: white;
  text-align: center;
}

.testimonials::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(255, 107, 107, 0.8), rgba(95, 39, 205, 0.8));
}

.testimonials .container {
  position: relative;
  z-index: 2;
}

.testimonials .section-title h2 {
  color: white;
}

.testimonials .section-title h2::after {
  background: white;
}

.testimonial-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
  margin-top: 50px;
}

.testimonial-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 15px;
  padding: 30px;
  text-align: left;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.testimonial-card p {
  font-style: italic;
  margin-bottom: 20px;
  line-height: 1.8;
}

.testimonial-author {
  display: flex;
  align-items: center;
}

.testimonial-author img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 15px;
  object-fit: cover;
  border: 2px solid white;
}

.author-info h4 {
  font-weight: 600;
  margin-bottom: 5px;
}

.author-info p {
  font-style: normal;
  font-size: 0.9rem;
  opacity: 0.8;
  margin: 0;
}

/* CTA Section */
.cta {
  padding: 100px 0;
  text-align: center;
  background: linear-gradient(135deg, var(--light), #f0f0f0);
}

.cta h2 {
  font-size: 2.5rem;
  margin-bottom: 20px;
  color: var(--dark);
}

.cta p {
  max-width: 700px;
  margin: 0 auto 40px;
  font-size: 1.2rem;
  color: #555;
}

.btn-cta {
  display: inline-block;
  background: linear-gradient(45deg, var(--primary), var(--secondary));
  color: white;
  padding: 15px 50px;
  border-radius: 50px;
  text-decoration: none;
  font-weight: 600;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);

}

.btn-cta:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

/* Footer */
footer {
  background: var(--dark);
  color: white;
  padding: 50px 0 20px;
  text-align: center;
}

.footer-logo {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 20px;
  background: linear-gradient(45deg, var(--primary), var(--secondary));
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  display: inline-block;
}

.footer-links {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  margin-bottom: 30px;
}

.footer-links a {
  color: white;
  text-decoration: none;
  margin: 0 15px;
  transition: color 0.3s ease;
}

.footer-links a:hover {
  color: var(--primary);
}

.social-icons {
  margin-bottom: 30px;
}

.social-icons a {
  display: inline-block;
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  margin: 0 10px;
  color: white;
  line-height: 40px;
  transition: all 0.3s ease;
}

.social-icons a:hover {
  background: var(--primary);
  transform: translateY(-5px);
}

.copyright {
  font-size: 0.9rem;
  opacity: 0.7;
}



/* Animations */
@keyframes float {
  0% {
    transform: translateY(0px);
  }

  50% {
    transform: translateY(-20px);
  }

  100% {
    transform: translateY(0px);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive */
@media (max-width: 768px) {
  .hero h1 {
    font-size: 3rem;
  }

  .hero p {
    font-size: 1.2rem;
  }

  .section-title h2 {
    font-size: 2.2rem;
  }

  .story-grid {
    grid-template-columns: 1fr;
  }

  .story-image {
    margin-bottom: 30px;
  }
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Crafty Corner</title>
  <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">

</head>

<body>

  <a href="index.php" style="
    display: inline-block;
    padding: 8px 15px;
    border: 2px solid #6f42c1;
    border-radius: 50px;
    color: #6f42c1;
    text-decoration: none;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    background-color: transparent;
    margin-top: 10px
">
    <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back to Website
  </a>
  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>The Crafty Corner</h1>
      <p>Where Creativity Meets Convenience in Every Gift</p>
      <a href="shop-grid.php" class="btn-hero">Explore Our Collection</a>
    </div>

    <!-- Floating decorative elements -->
    <img src="https://cdn-icons-png.flaticon.com/512/3069/3069172.png" class="floating floating-1" alt="Decoration">
    <img src="https://cdn-icons-png.flaticon.com/512/3069/3069195.png" class="floating floating-2" alt="Decoration">
    <img src="https://cdn-icons-png.flaticon.com/512/3069/3069224.png" class="floating floating-3" alt="Decoration">
  </section>

  <!-- Our Story Section -->
  <section class="story" id="about">
    <div class="container">
      <div class="section-title">
        <h2>Our Story</h2>
      </div>

      <div class="story-grid">
        <div class="story-image">
          <img
            src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
            alt="Crafty Corner Store">
        </div>

        <div class="story-content">
          <h3>From Small Beginnings to Creative Dreams</h3>
          <p>The Crafty Corner began as a small passion project in 2015, with just a handful of carefully selected
            stationary items and handmade gifts. What started as a way to share beautiful, meaningful products with our
            local community has blossomed into a thriving online destination for creative souls and gift-givers alike.
          </p>
          <p>Today, we curate the finest selection of stationary, greeting cards, gift articles, and creative supplies
            from around the world. Each product in our collection is chosen with care, ensuring it meets our high
            standards for quality, design, and emotional value.</p>
          <p>Our mission is simple: to help you express yourself beautifully and make gift-giving an unforgettable
            experience. Whether you're celebrating a special occasion or just want to brighten someone's day, The Crafty
            Corner has the perfect item to convey your feelings.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features">
    <div class="container">
      <div class="section-title">
        <h2>Why Choose Us</h2>
      </div>

      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-gem"></i>
          </div>
          <h3>Curated Quality</h3>
          <p>Every item in our collection is carefully selected for its exceptional quality, unique design, and ability
            to delight both giver and receiver.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-shipping-fast"></i>
          </div>
          <h3>Hassle-Free Delivery</h3>
          <p>Enjoy multiple delivery options with real-time tracking. We handle the logistics so you can focus on the
            joy of giving.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-heart"></i>
          </div>
          <h3>Thoughtful Selection</h3>
          <p>Our products tell stories and create connections. We help you find gifts that resonate deeply with your
            loved ones.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-headset"></i>
          </div>
          <h3>Exceptional Service</h3>
          <p>Our customer care team is passionate about making your experience perfect. Have questions? We're here to
            help!</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="testimonials">
    <div class="container">
      <div class="section-title">
        <h2>What Our Customers Say</h2>
      </div>

      <div class="testimonial-grid">
        <div class="testimonial-card">
          <p>"The Crafty Corner has become my go-to for all gifts. Every item I've purchased has been met with delight.
            Their greeting cards are especially beautiful!"</p>
          <div class="testimonial-author">
            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah J.">
            <div class="author-info">
              <h4>Sarah Johnson</h4>
              <p>Loyal Customer</p>
            </div>
          </div>
        </div>

        <div class="testimonial-card">
          <p>"I ordered a last-minute gift and was amazed by how quickly it arrived. The packaging was so beautiful it
            brought tears to my friend's eyes!"</p>
          <div class="testimonial-author">
            <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Michael T.">
            <div class="author-info">
              <h4>Michael Thompson</h4>
              <p>Happy Customer</p>
            </div>
          </div>
        </div>

        <div class="testimonial-card">
          <p>"The quality of their products is unmatched. I've purchased several handbags and wallets, and each one has
            held up beautifully over time."</p>
          <div class="testimonial-author">
            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Priya K.">
            <div class="author-info">
              <h4>Priya Kapoor</h4>
              <p>Frequent Shopper</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta" id="products">
    <div class="container">
      <h2>Ready to Find the Perfect Gift?</h2>
      <p>Browse our carefully curated collection of stationary, greeting cards, gift articles, and more. With our easy
        online ordering system, you can shop from anywhere and have your gifts delivered right to your door.</p>
      <a href="shop-grid.php" class="btn-cta">Shop Now</a>
    </div>
  </section>



  <script>
  // Animation for elements when they come into view
  const animateOnScroll = () => {
    const elements = document.querySelectorAll('.story-image, .story-content, .feature-card, .testimonial-card');

    elements.forEach(element => {
      const elementPosition = element.getBoundingClientRect().top;
      const screenPosition = window.innerHeight / 1.3;

      if (elementPosition < screenPosition) {
        element.style.opacity = '1';
        element.style.transform = 'translateY(0)';
      }
    });
  };

  // Set initial state
  document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.story-image, .story-content, .feature-card, .testimonial-card');
    elements.forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(30px)';
      el.style.transition = 'all 0.6s ease';
    });

    // Trigger animation on load for elements in view
    animateOnScroll();
  });

  // Add scroll event listener
  window.addEventListener('scroll', animateOnScroll);

  // Smooth scrolling for navigation
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();

      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });
  </script>
</body>

</html>