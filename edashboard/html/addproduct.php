<?php
include("components/header.php");

// Check if the user is logged in and get user type
$user_type = $_SESSION['user_type'] ?? '';

// Fetch categories
$query = $pdo->query("SELECT * FROM categories");
$categories = $query->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_type !== 'employee') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];
    $warranty_period = $_POST['warranty_period'];
    $image_path = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'images/products/'; // Ensure correct path
        
        // Ensure the directory exists
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory with full permissions
        }

        $file_name = time() . "_" . basename($_FILES['image']['name']); // Unique filename
        $file_path = $upload_dir . $file_name;
        $image_path = 'images/products/' . $file_name; // Relative path for database

        // Get the file extension
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        // List of allowed image formats
        $allowed_formats = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'tiff'];

        // Validate file format
        if (in_array($file_extension, $allowed_formats)) {
            // Validate if the file is an image using getimagesize()
            $image_info = getimagesize($_FILES['image']['tmp_name']);
            if ($image_info !== false) {
                // File is a valid image
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                    die("Failed to upload image.");
                }
            } else {
                die("Uploaded file is not a valid image.");
            }
        } else {
            die("Invalid file format. Allowed formats: " . implode(", ", $allowed_formats));
        }
    }

    // Generate a unique product ID (e.g., PE10001)
    $product_id = strtoupper(substr($product_name, 0, 2)) . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

    try {
        // Insert product into the database
        $stmt = $pdo->prepare("INSERT INTO products (product_id, product_name, description, price, stock_quantity, category_id, image_path, warranty_period) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$product_id, $product_name, $description, $price, $stock_quantity, $category_id, $image_path, $warranty_period]);

        echo "<script>location.assign('allproducts.php')</script>";
        exit();
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}
?>

<style>
.heading {
  font-size: 2.5rem;
  font-weight: 700;
  color: #7fad39;
  margin-bottom: 1rem;
  text-align: center;
}
</style>
<div class="container-fluid pt-4 px-4">
  <div class="row bg-light rounded mx-0">
    <div class="col-md-12">
      <h3 class="heading">Add New Product</h3>

      <?php if ($user_type === 'employee'): ?>
      <div class="alert alert-warning" role="alert">
        You do not have permission to add products. All fields are disabled.
      </div>
      <?php endif; ?>

      <form id="addProductForm" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="product_name" class="form-label">Product Name</label>
          <input type="text" class="form-control" id="product_name" name="product_name" required
            <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
        </div>

        <div class="mb-3">
          <label for="price" class="form-label">Price</label>
          <input type="number" class="form-control" id="price" name="price" required
            <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
        </div>

        <div class="mb-3">
          <label for="stock_quantity" class="form-label">Quantity</label>
          <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required
            <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
        </div>

        <div class="mb-3">
          <label for="warranty_period" class="form-label">Warranty Period (in months)</label>
          <input type="number" class="form-control" id="warranty_period" name="warranty_period" required
            <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea class="form-control" id="description" name="description" rows="3" required
            <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>></textarea>
        </div>

        <div class="mb-3">
          <label for="category_id" class="form-label">Category</label>
          <select class="form-select" id="category_id" name="category_id" required
            <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category) { ?>
            <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="image" class="form-label">Product Image</label>
          <input type="file" class="form-control" id="image" name="image"
            <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
          <small class="form-text text-muted">Allowed file types: JPG, JPEG, PNG, GIF, WEBP, BMP, TIFF.</small>
        </div>

        <button type="submit" class="btn btn-primary" <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>Add
          Product</button>
      </form>
    </div>
  </div>
</div>
<?php
include("components/footer.php");
?>

<script>
document.getElementById('addProductForm').addEventListener('submit', function(event) {
  // Get all input fields
  const productName = document.getElementById('product_name').value.trim();
  const price = document.getElementById('price').value.trim();
  const stockQuantity = document.getElementById('stock_quantity').value.trim();
  const warrantyPeriod = document.getElementById('warranty_period').value.trim();
  const description = document.getElementById('description').value.trim();
  const categoryId = document.getElementById('category_id').value.trim();
  const image = document.getElementById('image').value.trim();

  // Check if any field is empty
  if (!productName || !price || !stockQuantity || !warrantyPeriod || !description || !categoryId || !image) {
    alert('Please fill in all required fields.');
    event.preventDefault(); // Prevent form submission
  }
});
</script>