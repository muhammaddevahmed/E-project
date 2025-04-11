<?php
include("components/header.php");

// Check if the product ID is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>location.assign('allproducts.php')</script>";
    exit();
}

$product_id = (int)$_GET['id'];

// Fetch the product details from the database
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// If product not found, redirect to products page
if (!$product) {
    echo "<script>location.assign('allproducts.php')</script>";
    exit();
}

// Fetch categories for dropdown
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name']);
    $price = (float)$_POST['price'];
    $stock_quantity = (int)$_POST['stock_quantity'];
    $warranty_period = (int)$_POST['warranty_period'];
    $description = trim($_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $current_image = $product['image_path'];
    $new_image = null;
    $errors = [];

    // Validate inputs
    if (empty($product_name)) {
        $errors[] = "Product name is required";
    }
    if ($price <= 0) {
        $errors[] = "Price must be greater than 0";
    }
    if ($stock_quantity < 0) {
        $errors[] = "Stock quantity cannot be negative";
    }
    if ($warranty_period < 0) {
        $errors[] = "Warranty period cannot be negative";
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $target_dir = "images/products/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Generate unique filename
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_filename = time() . "_" . uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;

        // Validate image
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($file_ext, $allowed_types)) {
            $image_info = getimagesize($file['tmp_name']);
            if ($image_info !== false) {
                if (move_uploaded_file($file['tmp_name'], $target_file)) {
                    $new_image = $target_file;
                    
                    // Delete old image if it exists and is different from new one
                    if (!empty($current_image) && file_exists($current_image) && $current_image !== $new_image) {
                        unlink($current_image);
                    }
                } else {
                    $errors[] = "Failed to upload image";
                }
            } else {
                $errors[] = "Uploaded file is not a valid image";
            }
        } else {
            $errors[] = "Invalid file format. Allowed formats: " . implode(", ", $allowed_types);
        }
    } else {
        // Keep existing image if no new one uploaded
        $new_image = $current_image;
    }

    // Update product if no errors
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE products SET 
            product_name = ?, 
            price = ?, 
            stock_quantity = ?, 
            warranty_period = ?, 
            description = ?, 
            category_id = ?, 
            image_path = ? 
            WHERE product_id = ?");
        
        $stmt->execute([
            $product_name,
            $price,
            $stock_quantity,
            $warranty_period,
            $description,
            $category_id,
            $new_image,
            $product_id
        ]);

        echo "<script>location.assign('allproducts.php')</script>";
        exit();
    }
}
?>

<div class="container-fluid pt-4 px-4">
  <div class="row bg-light rounded mx-0">
    <div class="col-md-12">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Edit Product</h3>
        <a href="allproducts.php" class="btn btn-outline-secondary">Back to Products</a>
      </div>

      <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
        <p><?php echo $error; ?></p>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="product_name" class="form-label">Product Name *</label>
          <input type="text" class="form-control" id="product_name" name="product_name"
            value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
        </div>

        <div class="mb-3">
          <label for="price" class="form-label">Price *</label>
          <input type="number" step="0.01" class="form-control" id="price" name="price"
            value="<?php echo htmlspecialchars($product['price']); ?>" required>
        </div>

        <div class="mb-3">
          <label for="stock_quantity" class="form-label">Quantity *</label>
          <input type="number" class="form-control" id="stock_quantity" name="stock_quantity"
            value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>
        </div>

        <div class="mb-3">
          <label for="warranty_period" class="form-label">Warranty Period (months) *</label>
          <input type="number" class="form-control" id="warranty_period" name="warranty_period"
            value="<?php echo htmlspecialchars($product['warranty_period']); ?>" required>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description *</label>
          <textarea class="form-control" id="description" name="description" rows="3" required><?php 
                        echo htmlspecialchars($product['description']); 
                    ?></textarea>
        </div>

        <div class="mb-3">
          <label for="category_id" class="form-label">Category *</label>
          <select class="form-select" id="category_id" name="category_id" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['category_id']; ?>"
              <?php if ($category['category_id'] == $product['category_id']) echo 'selected'; ?>>
              <?php echo htmlspecialchars($category['category_name']); ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="image" class="form-label">Product Image</label>
          <input type="file" class="form-control" id="image" name="image" accept="image/*">
          <div class="form-text">
            <?php if (!empty($product['image_path']) && file_exists($product['image_path'])): ?>
            <div class="mt-2">
              <p>Current Image:</p>
              <img src="<?php echo $product['image_path']; ?>" class="img-thumbnail"
                style="max-width: 200px; max-height: 200px; object-fit: cover;" alt="Current product image">
              <p class="small text-muted mt-1"><?php echo basename($product['image_path']); ?></p>
            </div>
            <?php else: ?>
            <div class="text-warning">No image currently set for this product</div>
            <?php endif; ?>
            <div class="mt-1">Leave blank to keep current image. Allowed types: JPG, JPEG, PNG, GIF, WEBP.</div>
          </div>
        </div>

        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Update Product
          </button>
          <a href="allproducts.php" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include("components/footer.php"); ?>