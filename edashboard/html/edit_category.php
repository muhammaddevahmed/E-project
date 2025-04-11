<?php
include("components/header.php");

// Check if the category ID is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>location.assign('allcategories.php')</script>";
    exit();
}

$category_id = (int)$_GET['id'];

// Fetch the category details from the database
$stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
$stmt->execute([$category_id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

// If category not found, redirect to categories page
if (!$category) {
    echo "<script>location.assign('allcategories.php')</script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);
    $current_image = $category['image_path'];
    $new_image = null;
    $errors = [];

    // Validate category name
    if (empty($category_name)) {
        $errors[] = "Category name is required";
    } elseif (strlen($category_name) > 100) {
        $errors[] = "Category name must be less than 100 characters";
    }

    // Handle file upload if a new image is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $target_dir = "images/categories/";
        
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
    }

    // If no errors, update the category
    if (empty($errors)) {
        $image_to_use = $new_image ?: $current_image;
        
        $update_stmt = $pdo->prepare("UPDATE categories SET category_name = ?, image_path = ? WHERE category_id = ?");
        $update_stmt->execute([$category_name, $image_to_use, $category_id]);
        
        echo "<script>location.assign('allcategories.php')</script>";
        exit();
    } else {
        $error_message = implode("<br>", $errors);
    }
}
?>

<!-- Edit Category Form -->
<div class="container-fluid pt-4 px-4">
  <div class="row bg-light rounded mx-0">
    <div class="col-md-12">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Edit Category</h3>
        <a href="allcategories.php" class="btn btn-outline-secondary">Back to Categories</a>
      </div>

      <?php if (isset($error_message)): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($error_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="category_name" class="form-label">Category Name *</label>
          <input type="text" class="form-control" id="category_name" name="category_name"
            value="<?= htmlspecialchars($category['category_name']) ?>" required>
        </div>

        <div class="mb-3">
          <label for="image" class="form-label">Category Image</label>
          <input type="file" class="form-control" id="image" name="image" accept="image/*">
          <div class="form-text">
            <?php if (!empty($category['image_path']) && file_exists($category['image_path'])): ?>
            <div class="mt-2">
              <p>Current Image:</p>
              <img src="<?= htmlspecialchars($category['image_path']) ?>" class="img-thumbnail"
                style="max-width: 200px; max-height: 200px; object-fit: cover;" alt="Current category image">
              <p class="small text-muted mt-1"><?= htmlspecialchars(basename($category['image_path'])) ?></p>
            </div>
            <?php else: ?>
            <div class="text-warning">No image currently set for this category</div>
            <?php endif; ?>
            <div class="mt-1">Leave blank to keep current image. Allowed types: JPG, JPEG, PNG, GIF, WEBP.</div>
          </div>
        </div>

        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Update Category
          </button>
          <a href="allcategories.php" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
include("components/footer.php");