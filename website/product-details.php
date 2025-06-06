<?php
// Include database connection
include("components/header.php");

// Check session for logged-in user
$is_logged_in = isset($_SESSION['user_id']);
$full_name = $is_logged_in && isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "Guest User";

// Image path configuration
$web_root = 'http://localhost/EProject/'; // Adjust to your local URL
$actual_storage = 'edashboard/html/images/products/';
$default_image = $web_root . 'edashboard/html/images/default-product.jpg';

// Check if product ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details from the database
    $sql = "SELECT * FROM Products WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Process product image path
        $filename = basename($product['image_path']);
        $relative_path = $actual_storage . $filename;
        $absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/EProject/' . $relative_path;
        $image_url = $web_root . $relative_path;
        
        if (empty($product['image_path']) || !file_exists($absolute_path)) {
            $image_url = $default_image;
        }
        
        // Product found, assign details to variables
        $product_name = htmlspecialchars($product['product_name']);
        $description = htmlspecialchars($product['description']);
        $price = $product['price'];
        $stock_quantity = $product['stock_quantity'];
        $warranty_period = $product['warranty_period'];
    } else {
        // Product not found
        die("Product not found.");
    }
} else {
    // No product ID provided
    die("Invalid product ID.");
}

$sql = "SELECT * FROM Reviews WHERE product_id = :product_id ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate average rating
$sql = "SELECT AVG(rating) as avg_rating FROM Reviews WHERE product_id = :product_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
$stmt->execute();
$avg_rating = $stmt->fetch(PDO::FETCH_ASSOC)['avg_rating'];
$avg_rating = number_format($avg_rating, 1); // Format to 1 decimal place

// Fetch total number of reviews for the product
$sql = "SELECT COUNT(*) as total_reviews FROM Reviews WHERE product_id = :product_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
$stmt->execute();
$total_reviews = $stmt->fetch(PDO::FETCH_ASSOC)['total_reviews'];
?>

<style>
.quantity-control {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
}

.qty-btn {
  background: rgb(10, 162, 51);
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  border-radius: 3px;
  font-size: 16px;
}

.qty-btn1 {
  background: rgb(208, 35, 35);
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  border-radius: 3px;
  font-size: 16px;
}

.qty-btn:hover {
  background: rgb(119, 195, 154);
}

.qty-input {
  width: 50px;
  text-align: center;
  border: 1px solid #ccc;
  font-size: 16px;
  margin: 0 5px;
  padding: 3px;
  border-radius: 3px;
}

.btnNew {
  background-color: #7fad39;
}

.product__details__pic__item {
  width: 440px;
  height: 450px;
  border: 2px solid #ccc;
  border-radius: 10px;
  background-size: cover;
  background-position: center;
  margin-bottom: 15px;
  position: relative;
  /* Added for wishlist icon positioning */
}

.product__details__pic__item--large {
  max-width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
}

/* Wishlist Icon Styles */
.wishlist-icon {
  position: absolute;
  bottom: 10px;
  right: 10px;
  background: rgba(127, 173, 57, 0.9);
  color: white;
  padding: 8px;
  border-radius: 50%;
  opacity: 0;
  transition: opacity 0.3s ease, transform 0.3s ease;
  cursor: pointer;
  font-size: 16px;
}

.wishlist-icon:hover {
  background: #6a9a2b;
  transform: scale(1.1);
}

.wishlist-icon.added {
  color: #ff0000;
}

.product__details__pic__item:hover .wishlist-icon {
  opacity: 1;
}
</style>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg"
  data-setbg="https://i.pinimg.com/736x/72/e6/21/72e62198095a1c36038869ddf05481f7.jpg">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <div class="breadcrumb__text">
          <h2><?php echo $product_name; ?></h2>
          <div class="breadcrumb__option">
            <a href="./index.php">Home</a>
            <a href="./shop-grid.php">Products</a>
            <span><?php echo $product_name; ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Product Details Section Begin -->
<section class="product-details spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-6">
        <div class="product__details__pic">
          <div class="product__details__pic__item">
            <img class="product__details__pic__item--large" src="<?php echo $image_url; ?>"
              alt="<?php echo $product_name; ?>">
            <i class="fa fa-heart wishlist-icon" data-product-id="<?php echo $product_id; ?>"
              title="Add to Wishlist"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6">
        <div class="product__details__text">
          <h3><?php echo $product_name; ?></h3>
          <div class="product__details__rating">
            <?php
            $full_stars = floor($avg_rating);
            $half_star = ($avg_rating - $full_stars) >= 0.5;
            for ($i = 1; $i <= 5; $i++): ?>
            <?php if ($i <= $full_stars): ?>
            <i class="fa fa-star"></i>
            <?php elseif ($half_star && $i == $full_stars + 1): ?>
            <i class="fa fa-star-half-o"></i>
            <?php else: ?>
            <i class="fa fa-star-o"></i>
            <?php endif; ?>
            <?php endfor; ?>
            <span>(<?php echo count($reviews); ?> reviews)</span>
          </div>
          <div class="product__details__price">Rs <?php echo $price; ?></div>
          <p><?php echo $description; ?></p>
          <div class="quantity-control">
            <?php if ($stock_quantity > 0): ?>
            <form action="add_to_cart.php" method="POST" style="display: inline;" onsubmit="return validateQuantity()">
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
              <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
              <input type="hidden" name="price" value="<?php echo $price; ?>">
              <input type="hidden" name="image_path" value="<?php echo $image_url; ?>">
              <button type="submit" class="primary-btn">ADD TO CART</button>
              <button type="button" class="qty-btn1 dec">-</button>
              <input type="text" name="quantity" class="qty-input" value="1" min="1"
                max="<?php echo $stock_quantity; ?>" data-max="<?php echo $stock_quantity; ?>">
              <button type="button" class="qty-btn inc">+</button>
            </form>
            <?php else: ?>
            <button class="primary-btn" disabled style="background-color:rgb(228, 49, 49); cursor: not-allowed;">OUT OF
              STOCK</button>
            <?php endif; ?>
          </div>
          <ul>
            <li><b>Availability</b>
              <span><?php echo ($stock_quantity > 0) ? 'In Stock' : 'Out of Stock'; ?></span>
            </li>
            <li><b>Warranty</b> <span><?php echo $warranty_period; ?> months</span></li>
            <li><b>Share on</b>
              <div class="share">
                <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://twitter.com/"><i class="fa-brands fa-twitter"></i></a>
                <a href="https://www.linkedin.com"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="https://www.pinterest.com/"><i class="fa-brands fa-pinterest"></i></a>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="product__details__tab">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                aria-selected="true">Description</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab" aria-selected="false">Information</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab" aria-selected="false">
                Reviews <span>(<?php echo $total_reviews; ?>)</span>
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
              <div class="product__details__tab__desc">
                <h6>Products Information</h6>
                <p><?php echo $description; ?></p>
              </div>
            </div>
            <div class="tab-pane" id="tabs-2" role="tabpanel">
              <div class="product__details__tab__desc">
                <h6>Additional Information</h6>
                <p>Warranty: <?php echo $warranty_period; ?> months</p>
                <p>Stock Quantity: <?php echo $stock_quantity; ?></p>
              </div>
            </div>
            <div class="tab-pane" id="tabs-3" role="tabpanel">
              <div class="product__details__tab__desc">
                <h6>Customer Reviews</h6>
                <?php if (empty($reviews)): ?>
                <p>No reviews yet.</p>
                <?php else: ?>
                <div class="average-rating">
                  <h4>Average Rating: <?php echo $avg_rating; ?> / 5</h4>
                  <div class="stars">
                    <?php
                    $full_stars = floor($avg_rating);
                    $half_star = ($avg_rating - $full_stars) >= 0.5;
                    for ($i = 1; $i <= 5; $i++): ?>
                    <?php if ($i <= $full_stars): ?>
                    <i class="fa fa-star"></i>
                    <?php elseif ($half_star && $i == $full_stars + 1): ?>
                    <i class="fa fa-star-half-o"></i>
                    <?php else: ?>
                    <i class="fa fa-star-o"></i>
                    <?php endif; ?>
                    <?php endfor; ?>
                  </div>
                </div>
                <div class="reviews">
                  <?php foreach ($reviews as $review): ?>
                  <div class="review">
                    <h5><?php echo htmlspecialchars($review['user_name']); ?></h5>
                    <div class="rating">
                      <?php for ($i = 1; $i <= 5; $i++): ?>
                      <?php if ($i <= $review['rating']): ?>
                      <i class="fa fa-star"></i>
                      <?php else: ?>
                      <i class="fa fa-star-o"></i>
                      <?php endif; ?>
                      <?php endfor; ?>
                    </div>
                    <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                    <small><?php echo date('F j, Y', strtotime($review['created_at'])); ?></small>
                  </div>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Product Details Section End -->

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reviewModalLabel">Leave a Review</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="reviewForm" action="submit_review.php" method="POST">
          <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
          <div class="form-group">
            <label for="user_name">Your Name</label>
            <input type="text" class="form-control" id="user_name" name="user_name"
              value="<?php echo htmlspecialchars($full_name); ?>" required>
          </div>
          <div class="form-group">
            <label for="rating">Rating</label>
            <select class="form-control" id="rating" name="rating" required>
              <option value="1">1 Star</option>
              <option value="2">2 Stars</option>
              <option value="3">3 Stars</option>
              <option value="4">4 Stars</option>
              <option value="5">5 Stars</option>
            </select>
          </div>
          <div class="form-group">
            <label for="review_text">Review</label>
            <textarea class="form-control" id="review_text" name="review_text" rows="5" required></textarea>
          </div>
          <button type="submit" class="btn btn-success">Submit Review</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Center the button using Bootstrap -->
<div class="d-flex justify-content-center mb-4">
  <button type="button" class="btn btn-success review-btn" <?php echo !$is_logged_in ? 'disabled' : ''; ?>
    data-toggle="<?php echo $is_logged_in ? 'modal' : ''; ?>"
    data-target="<?php echo $is_logged_in ? '#reviewModal' : ''; ?>">
    Leave a Review
  </button>
</div>

<?php
// Add this code before including footer.php in product-details.php
if (isset($_SESSION['review_submitted'])) {
    unset($_SESSION['review_submitted']); // Clear the session variable
    echo '<script>alert("Thank you for your review! Your feedback has been submitted successfully.");</script>';
}
?>

<?php
// Include database connection
include("components/footer.php");
?>

<script>
// Quantity increment/decrement functionality
document.addEventListener('DOMContentLoaded', function() {
  const decButtons = document.querySelectorAll('.dec');
  const incButtons = document.querySelectorAll('.inc');
  const qtyInputs = document.querySelectorAll('.qty-input');
  const wishlistIcons = document.querySelectorAll('.wishlist-icon');
  const reviewButton = document.querySelector('.review-btn');

  decButtons.forEach(button => {
    button.addEventListener('click', function() {
      const input = this.nextElementSibling;
      let value = parseInt(input.value);
      if (value > 1) {
        input.value = value - 1;
      }
    });
  });

  incButtons.forEach(button => {
    button.addEventListener('click', function() {
      const input = this.previousElementSibling;
      let value = parseInt(input.value);
      const max = parseInt(input.getAttribute('data-max'));
      if (value < max) {
        input.value = value + 1;
      } else {
        alert('You are trying to add more than the available products in stock.');
      }
    });
  });

  // Prevent manual input exceeding stock
  qtyInputs.forEach(input => {
    input.addEventListener('input', function() {
      const max = parseInt(this.getAttribute('data-max'));
      let value = parseInt(this.value);
      if (isNaN(value) || value < 1) {
        this.value = 1;
      } else if (value > max) {
        this.value = max;
        alert('You are trying to add more than the available products in stock.');
      }
    });
  });

  // Wishlist functionality
  wishlistIcons.forEach(icon => {
    icon.addEventListener('click', function(e) {
      e.preventDefault();
      const productId = this.getAttribute('data-product-id');
      fetch('add_to_wishlist.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Product added to wishlist!');
            this.classList.add('added');
            location.reload();
          } else {
            alert(data.message || 'Please login to add to wishlist');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while adding to wishlist');
        });
    });
  });

  // Prevent review modal for non-logged-in users
  if (reviewButton && !<?php echo json_encode($is_logged_in); ?>) {
    reviewButton.addEventListener('click', function() {
      alert('Please login to leave a review.');
    });
  }
});

// Validate quantity on form submission
function validateQuantity() {
  const qtyInput = document.querySelector('.qty-input');
  const max = parseInt(qtyInput.getAttribute('data-max'));
  const value = parseInt(qtyInput.value);

  if (value > max) {
    alert('You are trying to add more than the available products in stock.');
    return false;
  }
  return true;
}
</script>