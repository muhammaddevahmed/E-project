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
          <h6>Join Our Newsletter</h6>
          <p>Stay updated with our latest products and exclusive offers.</p>
          <form action="#" class="newsletter-form">
            <div class="newsletter-input-group">
              <input type="email" placeholder="Enter your email" required>
              <button type="submit" class="site-btn">Subscribe</button>
            </div>
          </form>
          <div class="footer__widget__social">
            <a href="https://www.facebook.com/"><i class="fa fa-facebook"></i></a>
            <a href="https://twitter.com/"><i class="fa fa-twitter"></i></a>
            <a href="https://www.linkedin.com"><i class="fa fa-linkedin"></i></a>
            <a href="https://www.pinterest.com/"><i class="fa fa-pinterest-p"></i></a>
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

.footer__widget .newsletter-form {
  margin-bottom: 25px;
}

.footer__widget .newsletter-input-group {
  display: flex;
  gap: 10px;
  align-items: center;
  width: 100%;
}

.footer__widget input[type="email"] {
  flex: 1;
  padding: 12px;
  font-size: 15px;
  color: #333;
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: #fff;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
  width: 100%;
  box-sizing: border-box;
}

.footer__widget input[type="email"]:focus {
  border-color: #28a745;
  box-shadow: 0 0 10px rgba(40, 167, 69, 0.25);
  outline: none;
}

.footer__widget .site-btn {
  padding: 12px 25px;
  font-size: 16px;
  font-weight: 600;
  color: #fff;
  background-color: #28a745;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.footer__widget .site-btn:hover {
  background-color: #218838;
  transform: scale(1.08);
  box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
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

  .footer__widget .newsletter-input-group {
    flex-direction: row;
    /* Changed from column to row */
    gap: 10px;
    /* Reduced gap */
    align-items: stretch;
  }

  .footer__widget input[type="email"] {
    width: 70%;
    /* Give more space to input */
    padding: 10px;
    font-size: 14px;
  }

  .footer__widget .site-btn {
    width: 30%;
    /* Reduce button width */
    padding: 10px;
    font-size: 14px;
    /* Slightly smaller font */
  }
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

  .footer__widget .newsletter-input-group {
    flex-direction: row;
    /* Keep as row */
  }

  .footer__widget input[type="email"] {
    width: 65%;
    padding: 8px 10px;
  }

  .footer__widget .site-btn {
    width: 35%;
    padding: 8px 5px;
    font-size: 13px;
  }
}
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

  .footer__widget .newsletter-input-group {
    flex-direction: column;
    /* Stack vertically on smallest screens */
    gap: 8px;
  }

  .footer__widget input[type="email"],
  .footer__widget .site-btn {
    width: 100%;
    /* Full width when stacked */
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
<script src="js/add_cart.js"></script>
<script src="js/checkout.js"></script>
<script>
document.querySelectorAll('[data-setbg]').forEach(element => {
  const imagePath = element.getAttribute('data-setbg');
  element.style.backgroundImage = `url(${imagePath})`;
});
</script>

</body>

</html>