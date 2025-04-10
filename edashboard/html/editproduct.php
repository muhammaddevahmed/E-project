<?php
include("components/header.php");

// Check if the product ID is provided in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch the product details from the database
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // If product not found, redirect to products page
    if (!$product) {
        echo "<script>location.assign('allproducts.php')
        </script>";
        exit();
    }

    // Fetch categories for the category dropdown
    $query = $pdo->query("SELECT * FROM categories");
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $product_name = htmlspecialchars($_POST['product_name']);
    $price = htmlspecialchars($_POST['price']);
    $stock_quantity = htmlspecialchars($_POST['stock_quantity']);
    $warranty_period = htmlspecialchars($_POST['warranty_period']);
    $description = htmlspecialchars($_POST['description']);
    $category_id = $_POST['category_id'];

    // Initialize image path (keep the old image if no new image is uploaded)
    $image_path = $product['image_path'];

    // Handle the image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_path = $_FILES['image']['name'];
        $target_dir = "images/products/"; // Set the upload directory
        $target_file = $target_dir . basename($image_path);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allow only certain file types
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_file_type, $allowed_types)) {
            // Check if the file is an actual image
            if (getimagesize($_FILES['image']['tmp_name']) !== false) {
                // Move the uploaded file to the target directory
                move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
            } else {
                die("File is not an image.");
            }
        } else {
            die("Only JPG, JPEG, PNG, and GIF files are allowed.");
        }
    }

    // Update the product in the database
    $stmt = $pdo->prepare("UPDATE products SET product_name = ?, price = ?, stock_quantity = ?, warranty_period = ?, description = ?, category_id = ?, image_path = ? WHERE product_id = ?");
    $stmt->execute([$product_name, $price, $stock_quantity, $warranty_period, $description, $category_id, $image_path, $product_id]);

    // Redirect to the products page after updating the product
    echo "<script>location.assign('allproducts.php')
    </script>";
    exit();
}
?>

<!-- Edit Product Form -->
<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded mx-0">
        <div class="col-md-12">
            <h3>Edit Product</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="stock_quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="warranty_period" class="form-label">Warranty Period (in months)</label>
                    <input type="number" class="form-control" id="warranty_period" name="warranty_period" value="<?php echo htmlspecialchars($product['warranty_period']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?php echo $category['category_id']; ?>" <?php echo ($category['category_id'] == $product['category_id']) ? 'selected' : ''; ?>><?php echo $category['category_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <small class="form-text text-muted">Current image: <img src="<?php echo 'images/products/' . $product['image_path']; ?>" width="80" alt="Current Image"></small>
                </div>

                <button type="submit" class="btn btn-primary">Update Product</button>
            </form>
        </div>
    </div>
</div>

<?php
include("components/footer.php");
?>
