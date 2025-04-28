<?php
include("components/header.php");

// Check user type
$user_type = $_SESSION['user_type'] ?? '';

// Handle product deletion (only if the user is not an employee)
if (isset($_POST['deleteProduct'])) {
    if ($user_type !== 'employee') {
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
        
        $success = "Product deleted successfully!";
    } else {
        $error = "You do not have permission to delete products.";
    }
}

// Get all products with category names
$products = $pdo->query("
    SELECT products.*, categories.category_name 
    FROM products 
    LEFT JOIN categories ON products.category_id = categories.category_id
    ORDER BY products.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
.heading {
  font-size: clamp(1.8rem, 5vw, 2.5rem);
  font-weight: 700;
  color: #7fad39;
  margin-bottom: 1.5rem;
  text-align: center;
}

.table img {
  max-width: 80px;
  height: auto;
  object-fit: cover;
}

.btn {
  white-space: nowrap;
}

.d-flex.gap-2 {
  gap: 0.5rem !important;
}

/* Responsive adjustments */
@media (max-width: 992px) {
  .heading {
    font-size: clamp(1.6rem, 4vw, 2.2rem);
  }

  .table td,
  .table th {
    padding: 0.5rem;
    font-size: 0.9rem;
  }

  .btn {
    font-size: 0.85rem;
    padding: 0.25rem 0.5rem;
  }
}

@media (max-width: 576px) {
  .table img {
    max-width: 60px;
  }

  .alert {
    font-size: 0.9rem;
  }

  .modal-dialog {
    margin: 1rem;
  }

  .d-flex.gap-2 {
    flex-direction: column;
    align-items: start;
  }

  .btn-sm {
    width: 100%;
    text-align: center;
  }
}
</style>

<div class="container-fluid pt-4 px-2 px-md-4">
  <?php if (isset($success)): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <?php if (isset($error)): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <?php if ($user_type === 'employee'): ?>
  <div class="alert alert-warning" role="alert">
    You do not have permission to edit or delete products. All actions are disabled.
  </div>
  <?php endif; ?>

  <div class="row bg-light rounded mx-0 p-3">
    <div class="col-12">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
        <h3 class="heading">All Products</h3>
        <?php if ($user_type !== 'employee'): ?>
        <a href="addproduct.php" class="btn btn-primary mt-2 mt-md-0">Add New Product</a>
        <?php endif; ?>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-hover">
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
              <td class="align-middle"><?php echo htmlspecialchars($product['product_id']); ?></td>
              <td class="align-middle">
                <?php if (!empty($product['image_path']) && file_exists($product['image_path'])): ?>
                <img src="<?php echo $product['image_path']; ?>" class="img-fluid rounded"
                  alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                <?php else: ?>
                <span class="text-muted">No image</span>
                <?php endif; ?>
              </td>
              <td class="align-middle"><?php echo htmlspecialchars($product['product_name']); ?></td>
              <td class="align-middle">Rs <?php echo number_format($product['price'], 2); ?></td>
              <td class="align-middle"><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
              <td class="align-middle"><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
              <td class=" sclign-middle"><?php echo htmlspecialchars($product['warranty_period']); ?> months</td>
              <td class="align-middle"><?php echo date('Y-m-d', strtotime($product['created_at'])); ?></td>
              <td class="align-middle">
                <div class="d-flex gap-2">
                  <a href="editproduct.php?id=<?php echo $product['product_id']; ?>"
                    class="btn btn-sm btn-outline-primary"
                    <?php echo ($user_type === 'employee') ? 'onclick="return false;" style="pointer-events: none;"' : ''; ?>>
                    Edit
                  </a>
                  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                    data-bs-target="#deleteModal<?php echo $product['product_id']; ?>"
                    <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
                    Delete
                  </button>
                </div>
              </td>
            </tr>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal<?php echo $product['product_id']; ?>" tabindex="-1"
              aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
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
                      <button type="submit" name="deleteProduct" class="btn btn-danger"
                        <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>Delete</button>
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
</div>

<?php include("components/footer.php"); ?>