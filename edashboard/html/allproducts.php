<?php
include("components/header.php");

// Handle product deletion
if (isset($_POST['deleteProduct'])) {
    $product_id = $_POST['product_id'];
    
    // Get product data first
    $stmt = $pdo->prepare("SELECT image_path FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Delete image file if exists
    if ($product && !empty($product['image_path']) && file_exists($product['image_path'])) {
        unlink($product['image_path']);
    }
    
    // Delete product from database
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    
    header("Location: allproducts.php");
    exit();
}

// Get all products with category names
$products = $pdo->query("
    SELECT products.*, categories.category_name 
    FROM products 
    LEFT JOIN categories ON products.category_id = categories.category_id
    ORDER BY products.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid pt-4 px-4">
  <div class="row bg-light rounded mx-0">
    <div class="col-md-12">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>All Products</h3>
        <a href="addproduct.php" class="btn btn-primary">Add New Product</a>
      </div>

      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Category</th>
            <th>Warranty</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product): ?>
          <tr>
            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
            <td>
              <?php if (!empty($product['image_path']) && file_exists($product['image_path'])): ?>
              <img src="<?php echo $product['image_path']; ?>" width="80"
                alt="<?php echo htmlspecialchars($product['product_name']); ?>">
              <?php else: ?>
              <span class="text-muted">No image</span>
              <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
            <td>$<?php echo number_format($product['price'], 2); ?></td>
            <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
            <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($product['warranty_period']); ?> months</td>
            <td><?php echo date('Y-m-d', strtotime($product['created_at'])); ?></td>
            <td>
              <div class="d-flex gap-2">
                <a href="editproduct.php?id=<?php echo $product['product_id']; ?>"
                  class="btn btn-sm btn-outline-primary">Edit</a>
                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                  data-bs-target="#deleteModal<?php echo $product['product_id']; ?>">
                  Delete
                </button>
              </div>
            </td>
          </tr>

          <!-- Delete Modal -->
          <div class="modal fade" id="deleteModal<?php echo $product['product_id']; ?>" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Confirm Delete</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p>Are you sure you want to delete "<?php echo htmlspecialchars($product['product_name']); ?>"?</p>
                  <p class="text-danger">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                  <form method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="deleteProduct" class="btn btn-danger">Delete</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include("components/footer.php"); ?>