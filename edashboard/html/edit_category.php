<?php
include("components/header.php");

// Check if the category ID is provided in the URL
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    // Fetch the category details from the database
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    // If category not found, redirect to categories page
    if (!$category) {
        header("Location: allcategories.php");
        exit();
    }
} else {
    // If no category ID is provided, redirect to categories page
    header("Location: allcategories.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $category_name = htmlspecialchars($_POST['category_name']);
    $image_path = $_FILES['image']['name'];

    // Upload the image if a new one is provided
    if ($image_path) {
        $target_dir = "images/categories/";  // Set the upload directory
        $target_file = $target_dir . basename($image_path);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        $target_file = $category['image_path']; // Keep the current image if no new image is provided
    }

    // Update the category in the database
    $update_stmt = $pdo->prepare("UPDATE categories SET category_name = ?, image_path = ? WHERE category_id = ?");
    $update_stmt->execute([$category_name, $target_file, $category_id]);

    // Redirect back to categories page after the update
    // header("Location: allcategories.php");
    echo "<script>location.assign('allcategories.php')
    </script>";
    exit();
}

?>

<!-- Edit Category Form -->
<div class="container-fluid pt-4 px-4">
  <div class="row bg-light rounded mx-0">
    <div class="col-md-12">
      <h3>Edit Category</h3>

      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="category_name" class="form-label">Category Name</label>
          <input type="text" class="form-control" id="category_name" name="category_name"
            value="<?php echo $category['category_name']; ?>" required>
        </div>

        <div class="mb-3">
          <label for="image" class="form-label">Category Image</label>
          <input type="file" class="form-control" id="image" name="image">
          <small class="form-text text-muted">Current image: <img
              src="<?php echo $catImageAdd . $category['image_path']; ?>" width="100" alt="Current Image"></small>
        </div>

        <button type="submit" class="btn btn-primary">Update Category</button>
      </form>
    </div>
  </div>
</div>

<?php
include("components/footer.php");
?>