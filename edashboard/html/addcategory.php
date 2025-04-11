<?php
include("components/header.php");

// Check user type
$user_type = $_SESSION['user_type'] ?? '';

try {
    // Handle form submission only if the user is not an employee
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addCategory']) && $user_type !== 'employee') {
        $category_name = $_POST['catName'] ?? '';
        
        // Validate input
        if (empty($category_name)) {
            throw new Exception("Category name is required");
        }
        
        // Handle file upload
        if (isset($_FILES['catImage']) && $_FILES['catImage']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "images/categories/";
            
            // Create directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            // Get file info
            $file_name = $_FILES['catImage']['name'];
            $file_tmp = $_FILES['catImage']['tmp_name'];
            $file_size = $_FILES['catImage']['size'];
            $file_type = $_FILES['catImage']['type'];
            
            // Generate unique filename
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $new_filename = uniqid('cat_', true) . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;
            
            // Basic file validation
            if ($file_size > 5000000) { // 5MB limit
                throw new Exception("File is too large. Maximum size is 5MB.");
            }
            
            // Move uploaded file
            if (move_uploaded_file($file_tmp, $target_file)) {
                // Make sure the path is relative to your website root
                $relative_path = $target_file;
                
                // Insert into database using prepared statement
                $stmt = $pdo->prepare("INSERT INTO categories (category_name, image_path) VALUES (:name, :image)");
                $stmt->bindParam(':name', $category_name);
                $stmt->bindParam(':image', $relative_path);
                $stmt->execute();
                
                $success = "Category added successfully!";
                $uploaded_image = $relative_path; // Store for display
            } 
        } else {
            throw new Exception("Please select an image file");
        }
    }
} catch (Exception $e) {
    $error = "Error: " . $e->getMessage();
}

// Fetch existing categories to display
$categories = [];
try {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY category_id DESC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching categories: " . $e->getMessage();
}
?>

<div class="container-fluid pt-4 px-4">
  <div class="row bg-light rounded mx-0">
    <div class="col-md-12">
      <h3>Add a New Category</h3>

      <?php if ($user_type === 'employee'): ?>
      <div class="alert alert-warning" role="alert">
        You do not have permission to add categories. All fields are disabled.
      </div>
      <?php endif; ?>

      <?php if (isset($success)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="catName" class="form-label">Category Name</label>
          <input type="text" class="form-control" name="catName" id="catName" required
            <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
        </div>
        <div class="mb-3">
          <label for="catImage" class="form-label">Image</label>
          <input type="file" name="catImage" class="form-control" id="catImage" accept="image/*" required
            <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
          <small class="text-muted">All image formats are supported (Max 5MB)</small>

          <?php if (isset($uploaded_image)): ?>
          <div class="mt-2">
            <p>Uploaded Image:</p>
            <img src="<?= htmlspecialchars($uploaded_image) ?>" alt="Uploaded Category Image" class="img-thumbnail"
              style="max-width: 200px;">
          </div>
          <?php endif; ?>
        </div>

        <button type="submit" name="addCategory" class="btn btn-primary"
          <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>Add Category</button>
      </form>
    </div>
  </div>

  <?php
include("components/footer.php");
?>