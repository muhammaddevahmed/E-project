<?php
include("components/header.php");
?>
<section class="blog-content">
  <div class="container">
    <div class="blog-header">
      <h1>The Crafty Corner Journey</h1>
      <p class="subtitle">A story of creativity, craftsmanship, and community since 2020</p>
    </div>

    <article class="blog-post">
      <h2>A Passion for Handmade Artistry</h2>
      <img src="images/craft-workshop.jpg" alt="Craft Workshop">
      <p>
        In 2020, The Crafty Corner was born from a simple yet powerful idea: to celebrate the art of handmade creation.
        What began as a small group of artisans sharing their work has blossomed into a vibrant global community. Every
        piece on our platform, from intricate jewelry to cozy textiles, carries the heart and soul of its creator.
      </p>
      <p>
        Crafting is more than a hobby—it's a way to connect people and tell stories. At The Crafty Corner, we’re proud
        to provide a space where artisans and enthusiasts can inspire one another. Our journey is about fostering
        creativity, preserving traditions, and bringing joy through every handcrafted piece.
      </p>

      <img src="images/artisan-at-work.jpg" alt="Artisan at Work">
      <h2>Sustainability and Craftsmanship</h2>
      <p>
        Sustainability is woven into everything we do. We prioritize eco-friendly materials and ethical practices,
        ensuring that our crafts are kind to the planet. Supporting our artisans means supporting a movement for mindful
        creation, where every purchase helps sustain their craft and communities.
      </p>
      <p>
        From recycled materials to natural dyes, our artisans are innovators in sustainable design. We’re committed to
        showcasing their work while promoting a greener future for craftsmanship. This dedication sets us apart and
        fuels our mission to make a positive impact.
      </p>

      <h2>Looking Ahead</h2>
      <p>
        As we move forward, our vision remains clear: to empower creativity and build a community that celebrates the
        handmade. We’re excited about new initiatives, like virtual workshops, artisan collaborations, and exclusive
        collections that highlight emerging talent. The Crafty Corner is more than a marketplace—it’s a movement.
      </p>
      <p>
        Join us as we continue to craft stories, connect creators, and inspire the world, one handmade piece at a time.
        Whether you’re an artisan or a craft lover, there’s a place for you in our community.
      </p>
    </article>

    <!-- Timeline Section -->
    <section class="timeline">
      <h2>Our Milestones</h2>
      <div class="timeline-container">
        <div class="timeline-item">
          <div class="timeline-year">2020</div>
          <div class="timeline-content">
            <h3>A Spark of Creativity</h3>
            <p>The Crafty Corner is founded, uniting artisans to share their handmade creations.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-year">2021</div>
          <div class="timeline-content">
            <h3>Building a Community</h3>
            <p>Our online platform launches, connecting artisans with customers worldwide.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-year">2023</div>
          <div class="timeline-content">
            <h3>Inspiring Connections</h3>
            <p>Workshops and events spark creativity and collaboration among our community.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-year">2025</div>
          <div class="timeline-content">
            <h3>Crafting the Future</h3>
            <p>New collections and initiatives empower our creative community.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Call to Action -->
    <div class="cta-section">
      <h2>Join Our Creative Community</h2>
      <p class="text1">Be part of The Crafty Corner’s story and share your passion for craftsmanship.</p>
      <a href=" ./contact.php" class="cta-btn">Get Involved</a>
    </div>
  </div>
</section>
<!-- Blog Content Section End -->

<style>
/* Blog Page Styling */
.blog-content {
  padding: 80px 0;
  background: linear-gradient(180deg, #f9f9f9 0%, #e8f5e9 100%);
}

.blog-header {
  text-align: center;
  margin-bottom: 50px;
}

.blog-header h1 {
  font-size: 48px;
  font-weight: 800;
  color: #7fad39;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 15px;
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
}

.blog-header .subtitle {
  font-size: 20px;
  color: #555;
  font-weight: 300;
  max-width: 700px;
  margin: 0 auto;
}

.text1 {
  color: #fff
}

.blog-post {
  background: #fff;
  border-radius: 15px;
  padding: 40px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
  margin-bottom: 50px;
}

.blog-post h2 {
  font-size: 32px;
  color: #7fad39;
  margin-bottom: 20px;
  font-weight: 700;
  position: relative;
}

.blog-post h2::after {
  content: '';
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, #7fad39, #5f8c2a);
  position: absolute;
  bottom: -10px;
  left: 0;
  border-radius: 2px;
}

.blog-post p {
  font-size: 18px;
  color: #555;
  margin-bottom: 20px;
  text-align: justify;
  font-weight: 400;
}

.blog-post img {
  width: 100%;
  max-height: 400px;
  object-fit: cover;
  border-radius: 10px;
  margin: 20px 0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.blog-post img:hover {
  transform: scale(1.02);
}

/* Timeline Section */
.timeline {
  position: relative;
  padding: 60px 0;
}

.timeline h2 {
  font-size: 36px;
  color: #7fad39;
  text-align: center;
  margin-bottom: 40px;
  font-weight: 700;
}

.timeline::before {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  width: 6px;
  height: 100%;
  background: #7fad39;
  transform: translateX(-50%);
  border-radius: 3px;
}

.timeline-item {
  display: flex;
  align-items: center;
  margin-bottom: 50px;
  position: relative;
  animation: slideIn 0.8s ease-out;
}

.timeline-item:nth-child(even) {
  flex-direction: row-reverse;
}

.timeline-content {
  width: 45%;
  background: #fff;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.timeline-content:hover {
  transform: translateY(-8px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.timeline-content h3 {
  font-size: 26px;
  color: #7fad39;
  margin-bottom: 15px;
  font-weight: 600;
}

.timeline-content p {
  font-size: 16px;
  color: #555;
  font-weight: 400;
}

.timeline-year {
  width: 80px;
  height: 80px;
  text-align: center;
  font-size: 22px;
  font-weight: 700;
  color: #fff;
  background: #7fad39;
  padding: 10px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  z-index: 1;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* Call to Action */
.cta-section {
  background: linear-gradient(90deg, #7fad39, #5f8c2a);
  color: #fff;
  text-align: center;
  padding: 60px 20px;
  border-radius: 15px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.cta-section h2 {
  font-size: 32px;
  margin-bottom: 20px;
  font-weight: 700;
}

.cta-section p {
  font-size: 18px;
  max-width: 600px;
  margin: 0 auto 30px;
  font-weight: 300;
}

.cta-btn {
  padding: 15px 40px;
  font-size: 18px;
  font-weight: 600;
  color: #7fad39;
  background: #fff;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.cta-btn:hover {
  background: #f9f9f9;
  transform: scale(1.05);
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
}

/* Responsive Design */
@media (max-width: 1200px) {
  .blog-header h1 {
    font-size: 40px;
  }

  .blog-header .subtitle {
    font-size: 18px;
  }

  .blog-post h2 {
    font-size: 28px;
  }

  .blog-post img {
    max-height: 350px;
  }
}

@media (max-width: 991px) {
  .blog-content {
    padding: 60px 0;
  }

  .blog-header h1 {
    font-size: 36px;
  }

  .blog-post {
    padding: 30px;
  }

  .blog-post h2 {
    font-size: 26px;
  }

  .timeline::before {
    left: 40px;
  }

  .timeline-item {
    flex-direction: column;
    align-items: flex-start;
  }

  .timeline-item:nth-child(even) {
    flex-direction: column;
  }

  .timeline-content {
    width: 100%;
    margin-left: 80px;
  }

  .timeline-year {
    left: 40px;
    transform: none;
    width: 60px;
    height: 60px;
    font-size: 18px;
  }
}

@media (max-width: 767px) {
  .blog-content {
    padding: 40px 0;
  }

  .blog-header h1 {
    font-size: 30px;
  }

  .blog-header .subtitle {
    font-size: 16px;
  }

  .blog-post {
    padding: 20px;
  }

  .blog-post h2 {
    font-size: 24px;
  }

  .blog-post p {
    font-size: 16px;
  }

  .blog-post img {
    max-height: 300px;
  }

  .timeline h2 {
    font-size: 30px;
  }

  .timeline-content h3 {
    font-size: 22px;
  }

  .cta-section {
    padding: 40px 20px;
  }

  .cta-section h2 {
    font-size: 28px;
  }

  .cta-section p {
    font-size: 16px;
  }

  .cta-btn {
    padding: 12px 30px;
    font-size: 16px;
  }
}

@media (max-width: 576px) {
  .blog-header h1 {
    font-size: 24px;
  }

  .blog-header .subtitle {
    font-size: 14px;
  }

  .blog-post h2 {
    font-size: 22px;
  }

  .blog-post img {
    max-height: 250px;
  }

  .timeline h2 {
    font-size: 26px;
  }

  .timeline-content {
    margin-left: 60px;
  }

  .timeline-year {
    width: 50px;
    height: 50px;
    font-size: 16px;
  }

  .cta-section h2 {
    font-size: 24px;
  }
}

/* Animations */
@keyframes slideIn {
  0% {
    opacity: 0;
    transform: translateX(-50px);
  }

  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }

  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.blog-header,
.blog-post,
.timeline-item,
.cta-section {
  animation: fadeInUp 0.8s ease-out forwards;
}
</style>

<?php
include("components/footer.php");
?>

<script>
// Dynamic background image setting
document.querySelectorAll('[data-setbg]').forEach(element => {
  const imagePath = element.getAttribute('data-setbg');
  element.style.backgroundImage = `url(${imagePath})`;
});
</script>