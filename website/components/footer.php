<!-- Footer Section Begin -->
<footer class="footer spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="footer__about">
          <div class="footer__about__logo">
            <a href="./index.php"><img src="images/logo.png" alt="The Crafty Corner"></a>
          </div>
          <ul>
            <li><i class="fa fa-map-marker"></i> Address: Aptech, Shahra-e-Faisal</li>
            <li><i class="fa fa-phone"></i> Phone: +92 3442681140</li>
            <li><i class="fa fa-envelope"></i> Email: thecraftycorner@gmail.com</li>
          </ul>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="footer__widget">
          <h6>Customer Support</h6>
          <ul>
            <li><a href="./contact.php"><i class="fa fa-envelope"></i> Contact Us</a></li>
            <li><a href="./returns.php"><i class="fa fa-undo"></i> Returns</a></li>
            <li><a href="faqs.php"><i class="fa fa-question-circle"></i> FAQs</a></li>
            <li><a href="orders_item.php"><i class="fa fa-truck"></i> Shipping Info</a></li>
            <li><a href="orders_item.php"><i class="fa fa-search"></i> Order Tracking</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-5 col-md-12 col-sm-12">
        <div class="footer__widget">
          <h6>Creative Spotlight</h6>
          <p>Explore the artistry behind our handcrafted treasures.</p>
          <div class="spotlight-banner">
            <a href="article.php">
              <div class="spotlight-content">
                <i class="fa fa-star"></i>
                <span>Discover the Craft!</span>
              </div>
            </a>
          </div>
          <div class="footer__widget__social">
            <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook"></i></a>
            <a href="https://twitter.com/"><i class="fa-brands fa-twitter"></i></a>
            <a href="https://www.linkedin.com"><i class="fa-brands fa-linkedin-in"></i></a>
            <a href="https://www.pinterest.com/"><i class="fa-brands fa-pinterest"></i></a>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="footer__copyright">
          <div class="footer__copyright__text">
            <p>
              Copyright ©<script>
              document.write(new Date().getFullYear());
              </script> All rights reserved <i class="fa fa-heart" aria-hidden="true"></i> by <a href="./index.php">The
                Crafty Corner</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- Footer Section End -->

<style>
/* Footer Styling */
.footer {
  padding: 90px 0 40px;
  background-color: #f9f9f9;
  position: relative;
  overflow: hidden;
  animation: fadeIn 0.9s ease-in;
}

.footer::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(40, 167, 69, 0.08) 0%, rgba(0, 0, 0, 0.05) 100%);
  z-index: -1;
}

.footer__about__logo img {
  max-height: 75px;
  transition: transform 0.4s ease, filter 0.4s ease;
}

.footer__about__logo img:hover {
  transform: scale(1.15);
  filter: brightness(1.2);
}

.footer__about ul {
  margin-top: 20px;
}

.footer__about ul li {
  font-size: 16px;
  color: #333;
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  transition: color 0.3s ease, transform 0.3s ease;
}

.footer__about ul li i {
  font-size: 22px;
  color: #555;
  margin-right: 10px;
}

.footer__about ul li:hover {
  color: #28a745;
  transform: translateX(3px);
}

.footer__widget h6 {
  font-size: 20px;
  font-weight: 700;
  color: #333;
  margin-bottom: 25px;
  text-transform: uppercase;
  letter-spacing: 1px;
  position: relative;
  padding-bottom: 8px;
}

.footer__widget h6::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 60px;
  height: 3px;
  background-color: #28a745;
  border-radius: 1px;
}

.footer__widget ul li {
  margin-bottom: 12px;
}

.footer__widget ul li a {
  font-size: 16px;
  color: #555;
  display: flex;
  align-items: center;
  transition: color 0.3s ease, transform 0.3s ease;
}

.footer__widget ul li a i {
  font-size: 18px;
  margin-right: 8px;
  color: #555;
  transition: color 0.3s ease;
}

.footer__widget ul li a:hover {
  color: #28a745;
  transform: translateX(5px);
}

.footer__widget ul li a:hover i {
  color: #28a745;
}

.footer__widget p {
  font-size: 16px;
  color: #555;
  margin-bottom: 20px;
  line-height: 1.6;
}

.footer__widget .spotlight-banner {
  margin-bottom: 25px;
  width: 100%;
}

.footer__widget .spotlight-content {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 15px;
  font-size: 18px;
  font-weight: 600;
  color: #fff;
  background: #7fad39;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  width: 100%;
  text-align: center;
}

.footer__widget .spotlight-content i {
  font-size: 20px;
  margin-right: 10px;
  color: #fff;
}

.footer__widget .spotlight-content:hover {
  transform: scale(1.02);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
}

.footer__widget__social {
  display: flex;
  gap: 18px;
  justify-content: center;
  margin-top: 20px;
}

.footer__widget__social a {
  font-size: 24px;
  color: #555;
  transition: color 0.3s ease, transform 0.3s ease;
}

.footer__widget__social a:hover {
  color: #28a745;
  transform: scale(1.3);
}

.footer__copyright {
  margin-top: 40px;
  padding-top: 20px;
  border-top: 1px solid #ddd;
  text-align: center;
}

.footer__copyright__text p {
  font-size: 15px;
  color: #555;
  margin: 0;
  line-height: 1.6;
}

.footer__copyright__text a {
  color: #28a745;
  font-weight: 600;
  transition: color 0.3s ease;
}

.footer__copyright__text a:hover {
  color: #218838;
}

.footer__copyright__text i {
  color: #ff4c4c;
  font-size: 16px;
}

/* Responsive Design */
@media (max-width: 991px) {
  .footer {
    padding: 70px 0 30px;
  }

  .footer__about,
  .footer__widget {
    margin-bottom: 40px;
  }

  .footer__about__logo img {
    max-height: 60px;
  }

  .footer__widget h6 {
    font-size: 18px;
  }

  .footer__widget ul li a,
  .footer__widget p {
    font-size: 15px;
  }

  .footer__widget .spotlight-content {
    padding: 12px;
    font-size: 16px;
  }
}

@media (max-width: 767px) {
  .footer {
    padding: 60px 0 20px;
  }

  .footer__about ul li,
  .footer__widget ul li a,
  .footer__widget p {
    font-size: 14px;
  }

  .footer__about ul li i {
    font-size: 20px;
  }

  .footer__widget ul li a i {
    font-size: 16px;
  }

  .footer__widget__social a {
    font-size: 22px;
  }

  .footer__copyright__text p {
    font-size: 13px;
  }

  .footer__about,
  .footer__widget {
    text-align: center;
  }

  .footer__about ul li,
  .footer__widget ul li a {
    justify-content: center;
  }

  .footer__widget .spotlight-content {
    padding: 10px;
    font-size: 14px;
  }
}

@media (max-width: 576px) {
  .footer__about__logo img {
    max-height: 50px;
  }

  .footer__widget h6 {
    font-size: 16px;
  }

  .footer__widget h6::after {
    width: 50px;
  }

  .footer__widget__social {
    gap: 15px;
  }

  .footer__widget .spotlight-content {
    padding: 8px;
    font-size: 13px;
  }
}

/* Animation */
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
</style>

<!-- Js Plugins -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/jquery.slicknav.js"></script>
<script src="js/mixitup.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/main.js"></script>
<script src="js/checkout.js"></script>

<script>
document.querySelectorAll('[data-setbg]').forEach(element => {
  const imagePath = element.getAttribute('data-setbg');
  element.style.backgroundImage = `url(${imagePath})`;
});

// Add this to your main JavaScript file or before </body> tag
document.addEventListener('DOMContentLoaded', function() {
  // Initialize wishlist count on page load
  updateWishlistCount();

  // Set interval to periodically update count (optional)
  setInterval(updateWishlistCount, 30000); // Update every 30 seconds
});

// Function to update wishlist count
function updateWishlistCount() {
  fetch('get_wishlist_count.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        // Update all wishlist counters on the page
        document.querySelectorAll('#wishlist-item-count').forEach(el => {
          el.textContent = data.count;
        });
      }
    })
    .catch(error => {
      console.error('Error updating wishlist count:', error);
    });
}

// Function to handle wishlist toggle
function toggleWishlist(icon) {
  const productId = icon.getAttribute('data-product-id');
  const isActive = icon.classList.contains('active');

  fetch('wishlist_action.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `action=${isActive ? 'remove' : 'add'}&product_id=${productId}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Toggle active class
        icon.classList.toggle('active');

        // Update all instances of this product's wishlist icon
        document.querySelectorAll(`.wishlist-icon[data-product-id="${productId}"]`).forEach(el => {
          el.classList.toggle('active', !isActive);
        });

        // Update the wishlist count
        if (data.hasOwnProperty('count')) {
          document.querySelectorAll('#wishlist-item-count').forEach(el => {
            el.textContent = data.count;
          });
        } else {
          updateWishlistCount();
        }

        // Show toast notification
        showToast(isActive ? 'Product removed from wishlist' : 'Product added to wishlist');
      } else {
        if (data.redirect) {
          window.location.href = data.redirect;
        } else {
          showToast(data.message || 'An error occurred', 'error');
        }
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showToast('An error occurred. Please try again.', 'error');
    });
}

// Toast notification function
function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `toast-notification ${type}`;
  toast.innerHTML = `
        <i class="fa ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
        <span>${message}</span>
    `;

  document.body.appendChild(toast);

  setTimeout(() => {
    toast.classList.add('show');
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }, 10);
}

// Function to update notification count
function updateNotificationCount() {
  if (<?php echo $is_logged_in ? 'true' : 'false'; ?>) {
    fetch('get_notification_count.php')
      .then(response => response.json())
      .then(data => {
        document.getElementById('notification-count').textContent = data.count;
        document.querySelectorAll('.notification .badge').forEach(el => {
          el.textContent = data.count;
        });
      });
  }
}

// Check for new notifications every 30 seconds
setInterval(updateNotificationCount, 30000);

// Also update when page loads
document.addEventListener('DOMContentLoaded', updateNotificationCount);
</script>

</body>

</html>