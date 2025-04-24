<?php
include("components/header.php");

// Image path configuration
$web_root = 'http://localhost/EProject/'; // Adjust to your local URL
$actual_storage = 'edashboard/html/images/products/';
$default_image = $web_root . 'edashboard/html/images/default-product.jpg';

// Get sorting parameter or set default
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

// Build base query
$query = "SELECT SQL_CALC_FOUND_ROWS p.* FROM products p";
$conditions = [];
$params = [];

// Category filter
if ($category_id > 0) {
    $conditions[] = "p.category_id = :category_id";
    $params[':category_id'] = $category_id;
}

// Add WHERE clause if needed
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Sorting
switch ($sort) {
    case 'price_asc':
        $query .= " ORDER BY p.price ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY p.price DESC";
        break;
    case 'name_asc':
        $query .= " ORDER BY p.product_name ASC";
        break;
    case 'name_desc':
        $query .= " ORDER BY p.product_name DESC";
        break;
    default:
        $query .= " ORDER BY p.created_at DESC";
        break;
}

// Pagination
$query .= " LIMIT :limit OFFSET :offset";
$params[':limit'] = $limit;
$params[':offset'] = $offset;

// Prepare and execute
$stmt = $pdo->prepare($query);

// Bind parameters
foreach ($params as $key => $value) {
    $param_type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
    $stmt->bindValue($key, $value, $param_type);
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total product count
$total_products = $pdo->query("SELECT FOUND_ROWS()")->fetchColumn();
$total_pages = ceil($total_products / $limit);

// Image path fallback
foreach ($products as &$product) {
    $image_path = $actual_storage . $product['image_path'];
    $full_image_path = $_SERVER['DOCUMENT_ROOT'] . '/EProject/' . $image_path;

    $product['final_image_path'] = file_exists($full_image_path)
        ? $web_root . $image_path
        : $default_image;
}
unset($product); // break reference
?>

<style>
.product__item {
  margin-bottom: 30px;
  transition: all 0.3s;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  background: #fff;
}

.product__item:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.product__item__pic {
  height: 250px;
  width: 100%;
  background-size: cover;
  background-position: center;
  border-bottom: 1px solid #f0f0f0;
  transition: all 0.5s;
  position: relative;
  /* Added for wishlist icon positioning */
}

.product__item:hover .product__item__pic {
  opacity: 0.9;
}

.product__item__text {
  padding: 20px;
  text-align: center;
}

.product__item__text h6 a {
  color: #333;
  font-weight: 600;
  transition: all 0.3s;
  text-decoration: none;
}

.product__item__text h6 a:hover {
  color: #7fad39;
}

.product__item__text h5 {
  color: #7fad39;
  font-weight: 700;
  margin-top: 10px;
}

.product__pagination {
  margin-top: 30px;
  display: flex;
  justify-content: center;
}

.product__pagination a {
  display: inline-block;
  width: 40px;
  height: 40px;
  line-height: 40px;
  text-align: center;
  border-radius: 50%;
  margin: 0 5px;
  color: #333;
  font-weight: 600;
  transition: all 0.3s;
}

.product__pagination a.active,
.product__pagination a:hover {
  background: #7fad39;
  color: #fff;
}

/* Hero section adjustments */
.hero__item {
  margin-bottom: 30px;
}

/* Sidebar improvements */
.sidebar__item {
  background: #fff;
  padding: 20px;
  margin-bottom: 30px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.sidebar__item h4 {
  font-size: 18px;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 1px solid #f0f0f0;
  color: #333;
}

.sidebar__item ul li {
  padding: 8px 0;
  border-bottom: 1px solid #f5f5f5;
}

.sidebar__item ul li:last-child {
  border-bottom: none;
}

.sidebar__item ul li a {
  color: #666;
  transition: all 0.3s;
}

.sidebar__item ul li a:hover {
  color: #7fad39;
  padding-left: 5px;
}

/* Filter section */
.filter__item {
  background: #fff;
  padding: 15px;
  border-radius: 10px;
  margin-bottom: 30px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.filter__sort select {
  border: 1px solid #eee;
  padding: 8px 15px;
  border-radius: 5px;
  width: 100%;
  outline: none;
}

.filter__found h6 {
  font-size: 16px;
  color: #666;
  font-weight: 500;
}

.filter__found h6 span {
  color: #7fad39;
  font-weight: 700;
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
}

.wishlist-icon:hover {
  background: #6a9a2b;
  transform: scale(1.1);
}

.product__item__pic:hover .wishlist-icon {
  opacity: 1;
}
</style>

<!-- Hero Section Begin -->
<section class="hero mt-4">
  <div class="container">
    <div class="row">
      <!-- Main Hero Content -->
      <div class="col-lg-12">
        <!-- Promo Bar with Animation -->
        <div class="hero__promo mb-3"
          style="background: linear-gradient(to right, #7fad39, #5a8a1a); border-radius: 10px; animation: pulse 2s infinite;">
          <div class="promo__content py-2 px-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
              <div>
                <h1 style="color: white; font-weight: bold; font-size: 1.5rem; margin-bottom: 0;">
                  Welcome to Our Shop!
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
            <a href="#" class="btn px-4 py-2 text-white"
              style="background: #7fad39; border-radius: 30px; font-weight: 600; transition: all 0.3s;">
              Explore Collection <i class="fa fa-arrow-right ml-2"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Hero Section End -->

<!-- Product Section Begin -->
<section class="product spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-5">
        <div class="sidebar">
          <div class="sidebar__item">
            <h4>Categories</h4>
            <ul>
              <li><a href="shop-grid.php">All Categories</a></li>
              <?php foreach ($categories as $category): ?>
              <li>
                <a href="shop-grid.php?category_id=<?php echo $category['category_id']; ?>">
                  <?php echo htmlspecialchars($category['category_name']); ?>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-9 col-md-7">
        <div class="filter__item">
          <div class="row">
            <div class="col-lg-4 col-md-5">
              <div class="filter__sort">
                <span>Sort By</span>
                <select
                  onchange="window.location.href = 'shop-grid.php?sort=' + this.value + '&category_id=<?php echo $category_id; ?>'"
                  class="form-control">
                  <option value="default" <?php echo ($sort === 'default') ? 'selected' : ''; ?>>Default</option>
                  <option value="price_asc" <?php echo ($sort === 'price_asc') ? 'selected' : ''; ?>>Price: Low to High
                  </option>
                  <option value="price_desc" <?php echo ($sort === 'price_desc') ? 'selected' : ''; ?>>Price: High to
                    Low</option>
                  <option value="name_asc" <?php echo ($sort === 'name_asc') ? 'selected' : ''; ?>>Name: A to Z</option>
                  <option value="name_desc" <?php echo ($sort === 'name_desc') ? 'selected' : ''; ?>>Name: Z to A
                  </option>
                </select>
              </div>
            </div>
            <div class="col-lg-4 col-md-4">
              <div class="filter__found">
                <h6><span><?php echo $total_products; ?></span> Products found</h6>
              </div>
            </div>
            <div class="col-lg-4 col-md-3">
              <!-- Empty column for alignment -->
            </div>
          </div>
        </div>
        <div class="row">
          <?php foreach ($products as $product): ?>
          <?php
            // Fetch category name for the product
            $category_name = '';
            foreach ($categories as $category) {
                if ($category['category_id'] == $product['category_id']) {
                    $category_name = $category['category_name'];
                    break;
                }
            }

            // Process product image path
            $filename = basename($product['image_path']);
            $relative_path = $actual_storage . $filename;
            $absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/EProject/' . $relative_path;
            $image_url = $web_root . $relative_path;
            
            if (empty($product['image_path']) || !file_exists($absolute_path)) {
                $image_url = $default_image;
            }
          ?>
          <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="product__item">
              <div class="product__item__pic set-bg" data-setbg="<?php echo $image_url; ?>"
                style="background-image: url('<?php echo $image_url; ?>');">
                <i class="fa fa-heart wishlist-icon" data-product-id="<?php echo $product['product_id']; ?>"
                  title="Add to Wishlist"></i>
              </div>
              <div class="product__item__text">
                <h6><a href="product-details.php?id=<?php echo $product['product_id']; ?>">
                    <?php echo htmlspecialchars($product['product_name']); ?>
                  </a></h6>
                <h5>Rs <?php echo $product['price']; ?></h5>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="product__pagination">
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <a href="shop-grid.php?page=<?php echo $i; ?>&sort=<?php echo $sort; ?>&category_id=<?php echo $category_id; ?>"
            class="<?php echo ($page == $i) ? 'active' : ''; ?>">
            <?php echo $i; ?>
          </a>
          <?php endfor; ?>
          <?php if ($page < $total_pages): ?>
          <a
            href="shop-grid.php?page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>&category_id=<?php echo $category_id; ?>">
            <i class="fa fa-long-arrow-right"></i>
          </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Product Section End -->

<?php
include("components/footer.php");
?>

<script>
// Wishlist functionality
document.querySelectorAll('.wishlist-icon').forEach(icon => {
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
          this.style.color = '#ff0000';
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
</script>