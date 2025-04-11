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
</style>
<!-- Hero Section Begin -->
<section class="hero mt-4">
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <div class="hero__categories">
          <div class="hero__categories__all">
            <i class="fa fa-bars"></i>
            <span>All Categories</span>
          </div>
          <ul>
            <?php foreach ($categories as $category): ?>
            <li><a href="shop-grid.php"><?php echo htmlspecialchars($category['category_name']); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <div class="col-lg-9">
        <div class="hero__search">
          <div class="hero__search__form">
            <form action="#" method="GET" id="searchForm">
              <div class="hero__search__categories">
                All Categories
                <span class="arrow_carrot-down"></span>
              </div>
              <input type="text" name="search_query" id="searchInput" placeholder="What do you need?">
              <button type="submit" class="site-btn">SEARCH</button>
            </form>
            <!-- Search Results Container -->
            <div id="searchResults" class="search-results-container"></div>
          </div>
          <div class="hero__search__phone">
            <div class="hero__search__phone__icon">
              <i class="fa fa-phone"></i>
            </div>
            <div class="hero__search__phone__text">
              <h5>+65 11.188.888</h5>
              <span>support 24/7 time</span>
            </div>
          </div>
        </div>
        <div class="hero__item set-bg" data-setbg="images/Banner.png">
          <div class="hero__text"
            style="color: #F5E1C8; font-weight: bold; text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);">
            <span>Gifts and stationery shop</span>
            <h2>Shop Now <br />100% Original items</h2>
            <p>Free Pickup and Delivery Available</p>
            <a href="shop-grid.php" class="primary-btn">SHOP NOW</a>
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

<!-- Featured Section Begin -->
<section class="featured spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="section-title">
          <h2>Featured Products</h2>
        </div>
        <div class="featured__controls">
          <ul>
            <li class="active" data-filter="*">All</li>
            <?php
            $stmt = $pdo->query("SELECT DISTINCT category_name FROM categories");
            while ($category = $stmt->fetch(PDO::FETCH_ASSOC)):
              $category_class = strtolower(str_replace(' ', '-', $category['category_name']));
            ?>
            <li data-filter=".<?php echo $category_class; ?>">
              <?php echo htmlspecialchars($category['category_name']); ?>
            </li>
            <?php endwhile; ?>
          </ul>
        </div>
      </div>
    </div>

    <div class="row featured__filter">
      <?php
      $stmt = $pdo->query("SELECT products.*, categories.category_name 
                          FROM products 
                          JOIN categories ON products.category_id = categories.category_id");
      while ($product = $stmt->fetch(PDO::FETCH_ASSOC)):
        $category_class = strtolower(str_replace(' ', '-', $product['category_name']));
        
        // Implement the same image path logic
        $web_root = 'http://localhost/EProject/'; // Adjust to your local URL
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
            <h5>$<?php echo number_format($product['price'], 2); ?></h5>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>
<!-- Featured Section End -->


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
                  <span>$<?php echo number_format($product['price'], 2); ?></span>
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
                        echo '        <span>$' . number_format($product['price'], 2) . '</span>';
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
                        echo '        <span>$' . number_format($product['price'], 2) . '</span>';
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
</script>