<?php
include("php/db_connection.php"); // Include your database connection

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = 9; // Number of products to load per request

$stmt = $pdo->prepare("SELECT products.*, categories.category_name 
                       FROM products 
                       JOIN categories ON products.category_id = categories.category_id
                       LIMIT :offset, :limit");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $product) {
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
<div class="col-lg-4 col-md-6 col-sm-12 mix <?php echo strtolower(str_replace(' ', '-', $product['category_name'])); ?>"
  style="padding: 15px;">
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
<?php
}
?>