<?php
include("components/header.php");
?>
<style>
.set-bg {
  height: 300px;
  /* Adjust as needed */
  background-size: cover;
  background-position: center;

}

.featured__item__pic.set-bg {
  height: 350px;
  /* Adjust as needed */
}

.categories__item.set-bg {
  height: 250px;
  /* Adjust as needed */
}

.featured__item {
  margin: 15px;

  /* Adjust the value as needed */
  .featured__filter {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    /* Ensures even spacing */
    gap: 20px;
    /* Adjust the spacing between items */
  }

}

.btn,
.site-btn {
  background-color: #7fad39;
  color: #FFFFFF;
  border: none;
}

.featured__item__pic,
.latest-product__item__pic {
  border: 4px solid #ccc;
  border-radius: 10px;
}

/* Animation for promo bar */
@keyframes pulse {
  0% {
    transform: scale(1);
  }

  50% {
    transform: scale(1.01);
  }

  100% {
    transform: scale(1);
  }
}

/* Hover effects for categories */
.hero__categories ul li {
  transition: all 0.3s;
  border-bottom: 1px solid #f5f5f5;
}

.hero__categories ul li:last-child {
  border-bottom: none;
}

.hero__categories ul li:hover {
  background: #f8f9fa;
  padding-left: 10px;
}

.hero__categories ul li:hover a {
  color: #7fad39 !important;
}

/* Button hover effect */
.btn:hover {
  background: #6a9a2b !important;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}
</style>

<!-- Hero Section Begin -->
<section class="hero mt-4">
  <div class="container">
    <div class="row">
      <!-- Categories Sidebar - More Vibrant -->
      <div class="col-lg-3">
        <div class="hero__categories"
          style="background: #ffffff; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
          <div class="hero__categories__all" style="background: #7fad39; border-radius: 10px 10px 0 0;">
            <i class="fa fa-bars"></i>
            <span>All Categories</span>

          </div>
          <ul style="border-radius: 0 0 10px 10px;">
            <?php foreach ($categories as $category): ?>
            <li class="position-relative">
              <a href="shop-grid.php" class="d-flex justify-content-between align-items-center">
                <?php echo htmlspecialchars($category['category_name']); ?>
                <i class="fa fa-chevron-right"></i>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>



      </div>

      <!-- Main Hero Content - More Dynamic -->
      <div class="col-lg-9">
        <!-- Promo Bar with Animation -->
        <div class="hero__promo mb-3"
          style="background: linear-gradient(to right, #7fad39, #5a8a1a); border-radius: 10px; animation: pulse 2s infinite;">
          <div class="promo__content py-2 px-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
              <div>
                <h1 style="color: white; font-weight: bold; font-size: 1.5rem; margin-bottom: 0;">
                  Welcome to Our Store!
                </h1>
              </div>
              <div class="d-flex align-items-center">
                <i class="fa fa-phone-alt mr-2 text-white"></i>
                <span style="font-size: 1rem; color: white; font-weight: 500;">
                  (+92 346789900)
                </span>
                <span class="badge badge-light ml-2" style="color: #7fad39;">24/7 Support</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Hero Banner with Overlay -->
        <div class="hero__item position-relative overflow-hidden" style="border-radius: 10px; height: 400px;">
          <img src="images/Banner.png" class="w-100 h-100" style="object-fit: cover;" alt="Shop Banner">

          <!-- Gradient Overlay -->
          <div class="position-absolute w-100 h-100"
            style="background: linear-gradient(90deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%); top: 0; left: 0;">
          </div>

          <div class="hero__text position-absolute text-white"
            style="max-width: 500px; top: 50%; transform: translateY(-50%); left: 50px;">
            <span class="d-inline-block px-3 py-1 mb-2"
              style="background: rgba(127, 173, 57, 0.9); border-radius: 30px; font-size: 0.9rem;">
              Gifts and stationery shop
            </span>
            <h2 class="mb-3" style="font-size: 2.5rem; font-weight: 800; line-height: 1.2;">
              Shop Now <br />100% Original items
            </h2>
            <p class="mb-4" style="font-size: 1.1rem;">Free Pickup and Delivery Available</p>
            <a href="shop-grid.php" class="btn px-4 py-2 text-white"
              style="background: #7fad39; border-radius: 30px; font-weight: 600; transition: all 0.3s;">
              Explore Collection <i class="fa fa-arrow-right ml-2"></i>
            </a>
          </div>

          <!-- Decorative Elements -->
          <div class="position-absolute"
            style="bottom: 20px; right: 20px; width: 100px; height: 100px; border: 2px dashed rgba(255,255,255,0.3); border-radius: 50%;">
          </div>
          <div class="position-absolute"
            style="top: 20px; right: 20px; width: 50px; height: 50px; background: rgba(127, 173, 57, 0.7); border-radius: 50%;">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Hero Section End -->

<section class="categories">
  <div class="container">
    <div class="row">
      <div class="section-title">
        <h2>Categories</h2>
      </div>

      <?php if (!empty($categories)): ?>
      <div class="categories__slider owl-carousel">
        <?php foreach ($categories as $category): ?>
        <?php
            // 1. Define the correct base paths
            $web_root = 'http://localhost/EProject/'; // Adjust if your local URL is different
            $actual_storage = 'edashboard/html/images/categories/';
            $default_image = $web_root . 'edashboard/html/images/default-category.jpg';
            
            // 2. Extract filename from database path
            $filename = basename($category['image_path']);
            
            // 3. Create the correct paths
            $relative_path = $actual_storage . $filename;
            $absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/EProject/' . $relative_path;
            $image_url = $web_root . $relative_path;
            
            // 4. Verify the file exists
            if (!file_exists($absolute_path)) {
                $image_url = $default_image;
            }
            ?>

        <div class="col-lg-3">
          <div class="categories__item set-bg" data-setbg="<?php echo $image_url; ?>"
            style="background-image: url('<?php echo $image_url; ?>')">
            <h5>
              <a href="shop-grid.php?category=<?php echo htmlspecialchars($category['category_id']); ?>">
                <?php echo htmlspecialchars($category['category_name']); ?>
              </a>
            </h5>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="col-12">
        <div class="alert alert-warning">No categories found.</div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<section class="featured spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="section-title">
          <h2>Featured Products</h2>
        </div>
      </div>
    </div>

    <div class="row featured__filter" id="productContainer">
      <?php
      // Fetch the first 10 products
      $stmt = $pdo->query("SELECT products.*, categories.category_name 
                          FROM products 
                          JOIN categories ON products.category_id = categories.category_id
                          LIMIT 10");
      while ($product = $stmt->fetch(PDO::FETCH_ASSOC)):
        $category_class = strtolower(str_replace(' ', '-', $product['category_name']));
        
        // Image path logic
        $web_root = 'http://localhost/EProject/';
        $actual_storage = 'edashboard/html/images/products/';
        $default_image = $web_root . 'edashboard/html/images/default-product.jpg';
        
        $filename = basename($product['image_path']);
        $relative_path = $actual_storage . $filename;
        $absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/EProject/' . $relative_path;
        $image_url = $web_root . $relative_path;
        
        if (empty($product['image_path']) || !file_exists($absolute_path)) {
          $image_url = $default_image;
        }
      ?>
      <div class="col-lg-4 col-md-6 col-sm-12 mix <?php echo $category_class; ?>" style="padding: 15px;">
        <div class="featured__item">
          <div class="featured__item__pic set-bg" data-setbg="<?php echo $image_url; ?>"
            style="background-image: url('<?php echo $image_url; ?>');">
          </div>
          <div class="featured__item__text">
            <h6><a href="product-details.php?id=<?php echo $product['product_id']; ?>">
                <?php echo htmlspecialchars($product['product_name']); ?>
              </a></h6>
            <h5>Rs <?php echo number_format($product['price'], 2); ?></h5>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>

    <div class="row">
      <div class="col-lg-12 text-center">
        <button id="loadMoreBtn" class="btn mt-4">Show More</button>
      </div>
    </div>
  </div>
</section>

<!-- Banner Begin -->
<div class="banner mt-4">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="banner__pic">
          <img src="https://i.pinimg.com/736x/84/57/bb/8457bb3f81195119007a25701ed04b09.jpg" alt="">
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="banner__pic">
          <img src="https://i.pinimg.com/736x/cc/c0/ec/ccc0ec17f2efa897cf8aecb1be1c2df9.jpg" alt="">
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Banner End -->

<!-- Latest Product Section Begin -->
<section class="latest-product spad mt-4">
  <div class="container">
    <div class="row">

      <!-- Latest Products -->
      <div class="col-lg-4 col-md-6">
        <div class="latest-product__text">
          <h4>Latest Products</h4>
          <div class="latest-product__slider owl-carousel">
            <?php
            // Fetch the last 6 products from database
            $query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 6";
            $stmt = $pdo->query($query);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Image path configuration
            $web_root = 'http://localhost/EProject/'; // Adjust to your local URL
            $actual_storage = 'edashboard/html/images/products/';
            $default_image = $web_root . 'edashboard/html/images/default-product.jpg';
            
            // Split products into two groups for the carousel
            $productChunks = array_chunk($products, 3);
            ?>

            <?php foreach ($productChunks as $chunk): ?>
            <div class="latest-prdouct__slider__item">
              <?php foreach ($chunk as $product): 
                // Implement image path logic
                $filename = basename($product['image_path']);
                $relative_path = $actual_storage . $filename;
                $absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/EProject/' . $relative_path;
                $image_url = $web_root . $relative_path;
                
                if (empty($product['image_path']) || !file_exists($absolute_path)) {
                  $image_url = $default_image;
                }
              ?>
              <a href="product-details.php?id=<?php echo $product['product_id']; ?>" class="latest-product__item">
                <div class="latest-product__item__pic">
                  <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                </div>
                <div class="latest-product__item__text">
                  <h6><?php echo htmlspecialchars($product['product_name']); ?></h6>
                  <span>Rs <?php echo number_format($product['price'], 2); ?></span>
                </div>
              </a>
              <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Top Rated Products -->
      <div class="col-lg-4 col-md-6">
        <div class="latest-product__text">
          <h4>Top Rated Products</h4>
          <div class="latest-product__slider owl-carousel">
            <?php
            // Query to get top-rated products (average rating >= 3)
            $query = "SELECT p.*, AVG(r.rating) as avg_rating 
                      FROM products p
                      JOIN reviews r ON p.product_id = r.product_id
                      GROUP BY p.product_id
                      HAVING avg_rating >= 3
                      ORDER BY avg_rating DESC, p.created_at DESC
                      LIMIT 6";
            
            $stmt = $pdo->query($query);
            $topRatedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($topRatedProducts)) {
                echo '<div class="alert alert-info">No top-rated products available.</div>';
            } else {
                // Split products into chunks of 3 for the carousel
                $productChunks = array_chunk($topRatedProducts, 3);
                
                foreach ($productChunks as $chunk) {
                    echo '<div class="latest-prdouct__slider__item">';
                    foreach ($chunk as $product) {
                        // Implement image path logic
                        $filename = basename($product['image_path']);
                        $relative_path = $actual_storage . $filename;
                        $absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/EProject/' . $relative_path;
                        $image_url = $web_root . $relative_path;
                        
                        if (empty($product['image_path']) || !file_exists($absolute_path)) {
                          $image_url = $default_image;
                        }
                        
                        echo '<a href="product-details.php?id=' . $product['product_id'] . '" class="latest-product__item">';
                        echo '    <div class="latest-product__item__pic">';
                        echo '        <img src="' . $image_url . '" alt="' . htmlspecialchars($product['product_name']) . '">';
                        echo '    </div>';
                        echo '    <div class="latest-product__item__text">';
                        echo '        <h6>' . htmlspecialchars($product['product_name']) . '</h6>';
                        echo '        <span>Rs ' . number_format($product['price'], 2) . '</span>';
                        echo '        <div class="product-rating" style="font-size: 12px; color: #ffc107;">';
                        echo '            ' . str_repeat('★', round($product['avg_rating'])) . str_repeat('☆', 5 - round($product['avg_rating']));
                        echo '            (' . round($product['avg_rating'], 1) . ')';
                        echo '        </div>';
                        echo '    </div>';
                        echo '</a>';
                    }
                    echo '</div>';
                }
            }
            ?>
          </div>
        </div>
      </div>

      <!-- Recent Review Products -->
      <div class="col-lg-4 col-md-6">
        <div class="latest-product__text">
          <h4>Recent Review Products</h4>
          <div class="latest-product__slider owl-carousel">
            <?php
            // Query to get the last 6 products that have been reviewed
            $query = "SELECT p.*, r.rating, r.review_text, r.created_at as review_date
                      FROM products p
                      JOIN reviews r ON p.product_id = r.product_id
                      ORDER BY r.created_at DESC
                      LIMIT 6";
            
            $stmt = $pdo->query($query);
            $reviewedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($reviewedProducts)) {
                echo '<div class="alert alert-info">No reviewed products yet.</div>';
            } else {
                // Group products by product_id to avoid duplicates
                $uniqueProducts = [];
                foreach ($reviewedProducts as $product) {
                    if (!isset($uniqueProducts[$product['product_id']])) {
                        $uniqueProducts[$product['product_id']] = $product;
                        if (count($uniqueProducts) >= 6) break;
                    }
                }
                
                // Split into chunks of 3 for the carousel
                $productChunks = array_chunk($uniqueProducts, 3);
                
                foreach ($productChunks as $chunk) {
                    echo '<div class="latest-prdouct__slider__item">';
                    foreach ($chunk as $product) {
                        // Implement image path logic
                        $filename = basename($product['image_path']);
                        $relative_path = $actual_storage . $filename;
                        $absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/EProject/' . $relative_path;
                        $image_url = $web_root . $relative_path;
                        
                        if (empty($product['image_path']) || !file_exists($absolute_path)) {
                          $image_url = $default_image;
                        }
                        
                        echo '<a href="product-details.php?id=' . $product['product_id'] . '" class="latest-product__item">';
                        echo '    <div class="latest-product__item__pic">';
                        echo '        <img src="' . $image_url . '" alt="' . htmlspecialchars($product['product_name']) . '">';
                        echo '    </div>';
                        echo '    <div class="latest-product__item__text">';
                        echo '        <h6>' . htmlspecialchars($product['product_name']) . '</h6>';
                        echo '        <span>Rs ' . number_format($product['price'], 2) . '</span>';
                        echo '        <div class="product-review-info" style="font-size: 12px; margin-top: 5px;">';
                        echo '            <span style="color: #ffc107;">' . str_repeat('★', $product['rating']) . str_repeat('☆', 5 - $product['rating']) . '</span>';
                        echo '            <span style="color: #777; font-size: 11px;">' . date('M j, Y', strtotime($product['review_date'])) . '</span>';
                        echo '        </div>';
                        echo '    </div>';
                        echo '</a>';
                    }
                    echo '</div>';
                }
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Latest Product Section End -->

<?php

include("components/footer.php");
?>

<script src="https://cdn.jsdelivr.net/npm/mixitup@3/dist/mixitup.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  var mixer = mixitup('.featured__filter'); // Initialize MixItUp

  // Category button active state
  document.querySelectorAll('.featured__controls ul li').forEach(item => {
    item.addEventListener('click', function() {
      document.querySelectorAll('.featured__controls ul li').forEach(el => el.classList.remove('active'));
      this.classList.add('active');
    });
  });
});


document.addEventListener("DOMContentLoaded", function() {
  let offset = 9; // Start after the first 9 products
  const loadMoreBtn = document.getElementById("loadMoreBtn");
  const productContainer = document.getElementById("productContainer");

  loadMoreBtn.addEventListener("click", function() {
    fetch(`load_more_products.php?offset=${offset}`)
      .then(response => response.text())
      .then(data => {
        // Append the new products to the container
        productContainer.insertAdjacentHTML("beforeend", data);

        // Increment the offset
        offset += 9;

        // If no more products are returned, hide the button
        if (data.trim() === "") {
          loadMoreBtn.style.display = "none";
        }
      })
      .catch(error => console.error("Error loading more products:", error));
  });
});
</script>