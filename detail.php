<?php
// Include database connection
include("components/header.php");

// Check if product ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details from the database
    $sql = "SELECT * FROM Products WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Product found, assign details to variables
        $product_name = htmlspecialchars($product['product_name']);
        $description = htmlspecialchars($product['description']);
        $price = number_format($product['price'], 2);
        $image_path = htmlspecialchars($product['image_path']);
        $stock_quantity = $product['stock_quantity'];
        $warranty_period = $product['warranty_period'];
    } else {
        // Product not found
        die("Product not found.");
    }
} else {
    // No product ID provided
    die("Invalid product ID.");
}
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <!-- Dynamically display the product name -->
                    <h2><?php echo $product_name; ?></h2>
                    <div class="breadcrumb__option">
                        <a href="./index.html">Home</a>
                        <a href="./index.html">Products</a>
                        <!-- Dynamically display the product name -->
                        <span><?php echo $product_name; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Product Details Section Begin -->
<section class="product-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product__details__pic">
                    <div class="product__details__pic__item">
                        <!-- Dynamically display the product image -->
                        <img class="product__details__pic__item--large"
                            src="<?php echo $image_path; ?>" alt="<?php echo $product_name; ?>">
                    </div>
                    <!-- Add more images if needed -->
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <!-- Dynamically display the product name -->
                    <h3><?php echo $product_name; ?></h3>
                    <div class="product__details__rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star-half-o"></i>
                        <span>(18 reviews)</span>
                    </div>
                    <!-- Dynamically display the product price -->
                    <div class="product__details__price">$<?php echo $price; ?></div>
                    <!-- Dynamically display the product description -->
                    <p><?php echo $description; ?></p>
                    <div class="product__details__quantity">
                        <div class="quantity">
                            <div class="pro-qty">
                                <input type="text" value="1">
                            </div>
                        </div>
                    </div>
                    <a href="#" class="primary-btn">ADD TO CART</a>
                    <a href="#" class="heart-icon"><span class="icon_heart_alt"></span></a>
                    <ul>
                        <!-- Dynamically display availability -->
                        <li><b>Availability</b> <span><?php echo ($stock_quantity > 0) ? 'In Stock' : 'Out of Stock'; ?></span></li>
                        <!-- Dynamically display warranty period -->
                        <li><b>Warranty</b> <span><?php echo $warranty_period; ?> months</span></li>
                        <li><b>Share on</b>
                            <div class="share">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-pinterest"></i></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="product__details__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                                aria-selected="true">Description</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab"
                                aria-selected="false">Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                                aria-selected="false">Reviews <span>(1)</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Products Information</h6>
                                <!-- Dynamically display the product description -->
                                <p><?php echo $description; ?></p>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-2" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Additional Information</h6>
                                <!-- Dynamically display warranty and stock quantity -->
                                <p>Warranty: <?php echo $warranty_period; ?> months</p>
                                <p>Stock Quantity: <?php echo $stock_quantity; ?></p>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Customer Reviews</h6>
                                <p>No reviews yet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Details Section End -->

<?php
// Include database connection
include("components/footer.php");