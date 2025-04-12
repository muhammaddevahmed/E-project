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
.product__item__pic {
  width: 100%;
  /* Ensure the image takes full width of its container */

  border: 4px solid #ccc;
  /* Add a light gray border */
  border-radius: 10px;
  /* Add rounded corners */
  background-size: cover;
  /* Ensure the image covers the container */
  background-position: center;
  /* Center the image */
  margin-bottom: 15px;
  /* Add some spacing below the image */
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
          <div class="sidebar__item">
            <h4>Sort By</h4>
            <form method="GET" action="">
              <select name="sort" onchange="this.form.submit()">
                <option value="default" <?php echo ($sort === 'default') ? 'selected' : ''; ?>>Default</option>
                <option value="price_asc" <?php echo ($sort === 'price_asc') ? 'selected' : ''; ?>>Price: Low to High
                </option>
                <option value="price_desc" <?php echo ($sort === 'price_desc') ? 'selected' : ''; ?>>Price: High to Low
                </option>
                <option value="name_asc" <?php echo ($sort === 'name_asc') ? 'selected' : ''; ?>>Name: A to Z</option>
                <option value="name_desc" <?php echo ($sort === 'name_desc') ? 'selected' : ''; ?>>Name: Z to A</option>
              </select>
              <?php if ($category_id > 0): ?>
              <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
              <?php endif; ?>
            </form>
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
                  onchange="window.location.href = 'shop-grid.php?sort=' + this.value + '&category_id=<?php echo $category_id; ?>'">
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
                <!-- You can add hover effects or other elements here -->
              </div>
              <div class="product__item__text">
                <h6><a href="product-details.php?id=<?php echo $product['product_id']; ?>">
                    <?php echo htmlspecialchars($product['product_name']); ?>
                  </a></h6>
                <h5>Rs <?php echo number_format($product['price'], 2); ?></h5>
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