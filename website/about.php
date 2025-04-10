<style>
body {
  font-family: 'Poppins', sans-serif;
}

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

.floating {
  animation: float 6s ease-in-out infinite;
}

.floating-1 {
  animation-delay: 0s;
}

.floating-2 {
  animation-delay: 0.5s;
}

.floating-3 {
  animation-delay: 1s;
}

.animate-fade-in {
  animation: fadeIn 1s ease;
}

.animate-fade-in-down {
  animation: fadeInDown 1s ease;
}

.animate-fade-in-up {
  animation: fadeInUp 1s ease 0.3s both;
}

.animate-delayed-fade-in {
  animation: fadeIn 1s ease 0.6s both;
}

.clip-hero {
  clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
}

.clip-features {
  clip-path: polygon(0 10%, 100% 0, 100% 90%, 0 100%);
}

.bg-gradient-primary {
  background: linear-gradient(135deg, rgba(255, 107, 107, 0.9), rgba(95, 39, 205, 0.9));
}

.bg-gradient-features {
  background: linear-gradient(135deg, #f9f9f9, #ffffff);
}

.bg-gradient-text {
  background: linear-gradient(45deg, #ff6b6b, #5f27cd);
}

.bg-gradient-testimonial {
  background: linear-gradient(135deg, rgba(255, 107, 107, 0.8), rgba(95, 39, 205, 0.8));
}

.text-gradient {
  background: linear-gradient(45deg, #ff6b6b, #5f27cd);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

.border-gradient::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 5px;
  background: linear-gradient(90deg, #ff6b6b, #5f27cd);
}
</style>
<script src="https://cdn.tailwindcss.com"></script>
<?php
include("components/header.php");
?>







<body class="bg-[#fef6f6] text-[#222f3e] overflow-x-hidden">


  <!-- Hero Section -->
  <section
    class="h-[90vh] bg-gradient-primary bg-cover bg-center flex items-center justify-center text-center relative clip-hero mt-2"
    style="background-image: url('https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80')">
    <div class="max-w-4xl px-5 z-10">
      <h1 class="text-5xl md:text-7xl text-white mb-6 text-shadow-lg animate-fade-in-down">The Crafty Corner</h1>
      <p class="text-xl md:text-2xl text-white mb-8 font-light animate-fade-in-up">Where Creativity Meets Convenience in
        Every Gift</p>
      <a href="shop-grid.php"
        class="inline-block bg-[#7fad39] text-white px-10 py-4 rounded-full no-underline font-semibold text-lg animate-delayed-fade-in shadow-lg hover:-translate-y-1 hover:shadow-xl transition-all duration-300">Explore
        Our Collection</a>


      <!-- Floating decorative elements -->
      <img src="https://cdn-icons-png.flaticon.com/512/3069/3069172.png"
        class="floating floating-1 absolute w-20 top-1/5 left-1/10" alt="Decoration">
      <img src="https://cdn-icons-png.flaticon.com/512/3069/3069195.png"
        class="floating floating-2 absolute w-24 top-3/5 right-1/6" alt="Decoration">
      <img src="https://cdn-icons-png.flaticon.com/512/3069/3069224.png"
        class="floating floating-3 absolute w-16 bottom-1/10 left-1/5" alt="Decoration">
  </section>

  <!-- Our Story Section -->
  <section class="py-24 relative" id="about">
    <div class="container mx-auto px-5 max-w-6xl">
      <div class="text-center mb-16">
        <h2 class="text-4xl text-[#ff6b6b] relative inline-block mb-5">Our Story</h2>
        <div class="w-20 h-1 bg-[#1dd1a1] rounded absolute bottom-[-10px] left-1/2 transform -translate-x-1/2"></div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        <div
          class="relative rounded-2xl overflow-hidden shadow-xl transform -rotate-6 transition-transform duration-500 hover:rotate-0 hover:scale-105">
          <img
            src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
            alt="Crafty Corner Store" class="w-full h-auto block">
          <div class="absolute inset-0 bg-gradient-to-br from-[#ff6b6b] to-[#5f27cd] opacity-10"></div>
        </div>

        <div class="p-5">
          <h3 class="text-3xl text-[#5f27cd] mb-5">From Small Beginnings to Creative Dreams</h3>
          <p class="mb-4 leading-relaxed text-lg">The Crafty Corner began as a small passion project in 2015, with just
            a handful of carefully selected stationary items and handmade gifts. What started as a way to share
            beautiful, meaningful products with our local community has blossomed into a thriving online destination for
            creative souls and gift-givers alike.</p>
          <p class="mb-4 leading-relaxed text-lg">Today, we curate the finest selection of stationary, greeting cards,
            gift articles, and creative supplies from around the world. Each product in our collection is chosen with
            care, ensuring it meets our high standards for quality, design, and emotional value.</p>
          <p class="leading-relaxed text-lg">Our mission is simple: to help you express yourself beautifully and make
            gift-giving an unforgettable experience. Whether you're celebrating a special occasion or just want to
            brighten someone's day, The Crafty Corner has the perfect item to convey your feelings.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="py-24 bg-gradient-features clip-features -mt-12">
    <div class="container mx-auto px-5 max-w-6xl">
      <div class="text-center mb-16">
        <h2 class="text-4xl text-[#ff6b6b] relative inline-block mb-5">Why Choose Us</h2>
        <div class="w-20 h-1 bg-[#1dd1a1] rounded absolute bottom-[-10px] left-1/2 transform -translate-x-1/2"></div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mt-12">
        <div
          class="bg-white rounded-xl p-8 text-center relative overflow-hidden z-10 shadow-md hover:-translate-y-2 hover:shadow-lg transition-all duration-300 border-gradient">
          <div class="text-5xl mb-5 text-gradient">
            <i class="fas fa-gem"></i>
          </div>
          <h3 class="text-xl text-[#222f3e] mb-4">Curated Quality</h3>
          <p class="text-gray-600 leading-relaxed">Every item in our collection is carefully selected for its
            exceptional quality, unique design, and ability to delight both giver and receiver.</p>
        </div>

        <div
          class="bg-white rounded-xl p-8 text-center relative overflow-hidden z-10 shadow-md hover:-translate-y-2 hover:shadow-lg transition-all duration-300 border-gradient">
          <div class="text-5xl mb-5 text-gradient">
            <i class="fas fa-shipping-fast"></i>
          </div>
          <h3 class="text-xl text-[#222f3e] mb-4">Hassle-Free Delivery</h3>
          <p class="text-gray-600 leading-relaxed">Enjoy multiple delivery options with real-time tracking. We handle
            the logistics so you can focus on the joy of giving.</p>
        </div>

        <div
          class="bg-white rounded-xl p-8 text-center relative overflow-hidden z-10 shadow-md hover:-translate-y-2 hover:shadow-lg transition-all duration-300 border-gradient">
          <div class="text-5xl mb-5 text-gradient">
            <i class="fas fa-heart"></i>
          </div>
          <h3 class="text-xl text-[#222f3e] mb-4">Thoughtful Selection</h3>
          <p class="text-gray-600 leading-relaxed">Our products tell stories and create connections. We help you find
            gifts that resonate deeply with your loved ones.</p>
        </div>

        <div
          class="bg-white rounded-xl p-8 text-center relative overflow-hidden z-10 shadow-md hover:-translate-y-2 hover:shadow-lg transition-all duration-300 border-gradient">
          <div class="text-5xl mb-5 text-gradient">
            <i class="fas fa-headset"></i>
          </div>
          <h3 class="text-xl text-[#222f3e] mb-4">Exceptional Service</h3>
          <p class="text-gray-600 leading-relaxed">Our customer care team is passionate about making your experience
            perfect. Have questions? We're here to help!</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="py-24 bg-cover bg-center text-white text-center relative"
    style="background-image: url('https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80')">
    <div class="absolute inset-0 bg-gradient-testimonial"></div>
    <div class="container mx-auto px-5 max-w-6xl relative z-10">
      <div class="text-center mb-16">
        <h2 class="text-4xl text-white relative inline-block mb-5">What Our Customers Say</h2>
        <div class="w-20 h-1 bg-white rounded absolute bottom-[-10px] left-1/2 transform -translate-x-1/2"></div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 text-left border border-white/20">
          <p class="italic mb-5 leading-relaxed">"The Crafty Corner has become my go-to for all gifts. Every item I've
            purchased has been met with delight. Their greeting cards are especially beautiful!"</p>
          <div class="flex items-center">
            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah J."
              class="w-12 h-12 rounded-full mr-4 border-2 border-white">
            <div>
              <h4 class="font-semibold">Sarah Johnson</h4>
              <p class="text-sm opacity-80">Loyal Customer</p>
            </div>
          </div>
        </div>

        <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 text-left border border-white/20">
          <p class="italic mb-5 leading-relaxed">"I ordered a last-minute gift and was amazed by how quickly it arrived.
            The packaging was so beautiful it brought tears to my friend's eyes!"</p>
          <div class="flex items-center">
            <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Michael T."
              class="w-12 h-12 rounded-full mr-4 border-2 border-white">
            <div>
              <h4 class="font-semibold">Michael Thompson</h4>
              <p class="text-sm opacity-80">Happy Customer</p>
            </div>
          </div>
        </div>

        <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 text-left border border-white/20">
          <p class="italic mb-5 leading-relaxed">"The quality of their products is unmatched. I've purchased several
            handbags and wallets, and each one has held up beautifully over time."</p>
          <div class="flex items-center">
            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Priya K."
              class="w-12 h-12 rounded-full mr-4 border-2 border-white">
            <div>
              <h4 class="font-semibold">Priya Kapoor</h4>
              <p class="text-sm opacity-80">Frequent Shopper</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-24 text-center bg-gradient-to-b from-[#f9f9f9] to-[#f0f0f0]" id="products">
    <div class="container mx-auto px-5 max-w-4xl">
      <h2 class="text-4xl text-[#222f3e] mb-5">Ready to Find the Perfect Gift?</h2>
      <p class="text-xl text-gray-600 mb-10 max-w-3xl mx-auto">Browse our carefully curated collection of stationary,
        greeting cards, gift articles, and more. With our easy online ordering system, you can shop from anywhere and
        have your gifts delivered right to your door.</p>
      <a href="shop-grid.php"
        class="inline-block bg-gradient-to-r from-[#7fad39] to-[#7fad39] text-white px-12 py-4 rounded-full no-underline font-semibold text-lg shadow-md hover:-translate-y-1 hover:shadow-lg transition-all duration-300">Shop
        Now</a>


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
<?php
include("components/footer.php");
?>