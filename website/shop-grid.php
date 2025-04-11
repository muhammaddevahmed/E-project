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



<!-- Hero Section Begin -->
<section class="hero hero-normal">
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <div class="hero__categories">
          <div class="hero__categories__all">
            <i class="fa fa-bars"></i>
            <span>All departments</span>
          </div>
          <ul>
            <li><a href="#">Fresh Meat</a></li>
            <li><a href="#">Vegetables</a></li>
            <li><a href="#">Fruit & Nut Gifts</a></li>
            <li><a href="#">Fresh Berries</a></li>
            <li><a href="#">Ocean Foods</a></li>
            <li><a href="#">Butter & Eggs</a></li>
            <li><a href="#">Fastfood</a></li>
            <li><a href="#">Fresh Onion</a></li>
            <li><a href="#">Papayaya & Crisps</a></li>
            <li><a href="#">Oatmeal</a></li>
            <li><a href="#">Fresh Bananas</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-9">
        <div class="hero__search">
          <div class="hero__search__form">
            <form action="#">
              <div class="hero__search__categories">
                All Categories
                <span class="arrow_carrot-down"></span>
              </div>
              <input type="text" placeholder="What do yo u need?">
              <button type="submit" class="site-btn">SEARCH</button>
            </form>
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
              <div class="filter__option">
                <span class="icon_grid-2x2"></span>
                <span class="icon_ul"></span>
              </div>
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
                <h5>$<?php echo number_format($product['price'], 2); ?></h5>
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