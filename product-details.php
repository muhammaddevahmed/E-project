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

$sql = "SELECT * FROM Reviews WHERE product_id = :product_id ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate average rating
$sql = "SELECT AVG(rating) as avg_rating FROM Reviews WHERE product_id = :product_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
$stmt->execute();
$avg_rating = $stmt->fetch(PDO::FETCH_ASSOC)['avg_rating'];
$avg_rating = number_format($avg_rating, 1); // Format to 1 decimal place

// Fetch total number of reviews for the product
$sql = "SELECT COUNT(*) as total_reviews FROM Reviews WHERE product_id = :product_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
$stmt->execute();
$total_reviews = $stmt->fetch(PDO::FETCH_ASSOC)['total_reviews'];


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
                        <img class="product__details__pic__item--large" src="<?php echo $image_path; ?>"
                            alt="<?php echo $product_name; ?>">
                    </div>
                    <!-- Add more images if needed -->
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <!-- Dynamically display the product name -->
                    <h3><?php echo $product_name; ?></h3>

                    <div class="product__details__rating">
                        <?php
    $full_stars = floor($avg_rating);
    $half_star = ($avg_rating - $full_stars) >= 0.5;
    for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= $full_stars): ?>
                        <i class="fa fa-star"></i>
                        <?php elseif ($half_star && $i == $full_stars + 1): ?>
                        <i class="fa fa-star-half-o"></i>
                        <?php else: ?>
                        <i class="fa fa-star-o"></i>
                        <?php endif; ?>
                        <?php endfor; ?>
                        <span>(<?php echo count($reviews); ?> reviews)</span>
                    </div>
                    <!-- Dynamically display the product price -->
                    <div class="product__details__price">$<?php echo $price; ?></div>
                    <!-- Dynamically display the product description -->
                    <p><?php echo $description; ?></p>

                    <!-- Add to Cart Form -->
                    <!-- Add to Cart Form -->
                    <!-- Add to Cart Form -->
                    <form action="add_to_cart.php" method="POST" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
                        <input type="hidden" name="price" value="<?php echo $price; ?>">
                        <input type="hidden" name="image_path" value="<?php echo $image_path; ?>">
                        <input type="hidden" name="quantity" value="1"> <!-- Default quantity is 1 -->
                        <button type="submit" class="primary-btn">ADD TO CART</button>
                    </form>


                    <ul>
                        <!-- Dynamically display availability -->
                        <li><b>Availability</b>
                            <span><?php echo ($stock_quantity > 0) ? 'In Stock' : 'Out of Stock'; ?></span>
                        </li>
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
                            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab" aria-selected="false">
                                Reviews <span>(<?php echo $total_reviews; ?>)</span>
                            </a>
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
                        <!-- Display Reviews -->
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Customer Reviews</h6>
                                <?php if (empty($reviews)): ?>
                                <p>No reviews yet.</p>
                                <?php else: ?>
                                <!-- Display average rating -->
                                <div class="average-rating">
                                    <h4>Average Rating: <?php echo $avg_rating; ?> / 5</h4>
                                    <div class="stars">
                                        <?php
                    $full_stars = floor($avg_rating);
                    $half_star = ($avg_rating - $full_stars) >= 0.5;
                    for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $full_stars): ?>
                                        <i class="fa fa-star"></i>
                                        <?php elseif ($half_star && $i == $full_stars + 1): ?>
                                        <i class="fa fa-star-half-o"></i>
                                        <?php else: ?>
                                        <i class="fa fa-star-o"></i>
                                        <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <!-- Display individual reviews -->
                                <div class="reviews">
                                    <?php foreach ($reviews as $review): ?>
                                    <div class="review">
                                        <h5><?php echo htmlspecialchars($review['user_name']); ?></h5>
                                        <div class="rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review['rating']): ?>
                                            <i class="fa fa-star"></i>
                                            <?php else: ?>
                                            <i class="fa fa-star-o"></i>
                                            <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                        <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                                        <small><?php echo date('F j, Y', strtotime($review['created_at'])); ?></small>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<!-- Product Details Section End -->


<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Leave a Review</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reviewForm" action="submit_review.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <div class="form-group">
                        <label for="user_name">Your Name</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" required>
                    </div>
                    <div class="form-group">
                        <label for="rating">Rating</label>
                        <select class="form-control" id="rating" name="rating" required>
                            <option value="1">1 Star</option>
                            <option value="2">2 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="5">5 Stars</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="review_text">Review</label>
                        <textarea class="form-control" id="review_text" name="review_text" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Center the button using Bootstrap -->
<div class="d-flex justify-content-center mb-4">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reviewModal">
        Leave a Review
    </button>
</div>

<?php
// Include database connection
include("components/footer.php");