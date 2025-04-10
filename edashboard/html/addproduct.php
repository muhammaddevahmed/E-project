<?php
include("components/header.php");

$query = $pdo->query("SELECT * FROM categories");
$categories = $query->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = htmlspecialchars($_POST['product_name']);
    $price = htmlspecialchars($_POST['price']);
    $stock_quantity = htmlspecialchars($_POST['stock_quantity']);
    $warranty_period = htmlspecialchars($_POST['warranty_period']);
    $description = htmlspecialchars($_POST['description']);
    $category_id = $_POST['category_id'];
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_path = $_FILES['image']['name'];
        $target_dir = "images/products/";
        $target_file = $target_dir . basename($image_path);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_file_type, $allowed_types)) {
            if (getimagesize($_FILES['image']['tmp_name']) !== false) {
                move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
            } else {
                die("File is not an image.");
            }
        } else {
            die("Only JPG, JPEG, PNG, and GIF files are allowed.");
        }
    }

    $stmt = $pdo->prepare("INSERT INTO products (product_name, price, stock_quantity, warranty_period, description, category_id, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$product_name, $price, $stock_quantity, $warranty_period, $description, $category_id, $image_path]);

    echo "<script>location.assign('allproducts.php')
    </script>";
    exit();
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded mx-0">
        <div class="col-md-12">
            <h3>Add New Product</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" required>
                </div>

                <div class="mb-3">
                    <label for="stock_quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
                </div>

                <div class="mb-3">
                    <label for="warranty_period" class="form-label">Warranty Period (in months)</label>
                    <input type="number" class="form-control" id="warranty_period" name="warranty_period" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <small class="form-text text-muted">Allowed file types: JPG, JPEG, PNG, GIF.</small>
                </div>

                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>
    </div>
</div>

<?php
include("components/footer.php");
?>
