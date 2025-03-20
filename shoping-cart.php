<style>
    .shoping__cart__item img {
        width: 100px; /* Set a fixed width */
        height: 100px; /* Set a fixed height */
        object-fit: cover; /* Ensure the image covers the area without distortion */
    }
</style>

<?php
include("components/header.php");



// Fetch cart items for the user
$user_id = $_SESSION['user_id'] ?? 0; // Replace with actual user ID if you have user authentication
$sql = "SELECT Cart.*, Products.product_name, Products.price, Products.image_path 
        FROM Cart 
        JOIN Products ON Cart.product_id = Products.product_id 
        WHERE Cart.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate subtotal and total
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal; // Add tax/shipping if needed

// Handle delete action
if (isset($_GET['delete_cart_id']) && !empty($_GET['delete_cart_id'])) {
    $cart_id = $_GET['delete_cart_id'];

    // Delete the item from the cart
    $sql = "DELETE FROM Cart WHERE cart_id = :cart_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect back to the cart page
    header("Location: shoping-cart.php");
    exit();
}

// Handle update quantity action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = intval($_POST['quantity']);

    // Validate input
    if (empty($cart_id) || empty($quantity) || $quantity < 1) {
        die("Invalid input.");
    }

    // Update the quantity in the cart
    $sql = "UPDATE Cart SET quantity = :quantity WHERE cart_id = :cart_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->execute();

    // Return a success response
    echo json_encode(['status' => 'success', 'message' => 'Cart updated successfully.']);
    exit();
}
?>

<!-- Shoping Cart Section Begin -->
<section class="shoping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__table">
                    <table>
                        <thead>
                            <tr>
                                <th class="shoping__product">Products</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td class="shoping__cart__item">
                                        <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['product_name']; ?>">
                                        <h5><?php echo $item['product_name']; ?></h5>
                                    </td>
                                    <td class="shoping__cart__price">
                                        $<?php echo number_format($item['price'], 2); ?>
                                    </td>
                                    <td class="shoping__cart__quantity">
                                        <div class="quantity">
                                            <div class="pro-qty">
                                                
                                                <input type="text" class="qty-input" value="<?php echo $item['quantity']; ?>" data-price="<?php echo $item['price']; ?>" data-cart-id="<?php echo $item['cart_id']; ?>">
                                                
                                            </div>
                                        </div>
                                    </td>
                                    <td class="shoping__cart__total">
                                        $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        <a href="shoping-cart.php?delete_cart_id=<?php echo $item['cart_id']; ?>" class="icon_close delete-item"></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__btns">
                    <a href="#" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                    <a href="#" class="primary-btn cart-btn cart-btn-right"><span class="icon_loading"></span> Update Cart</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shoping__continue">
                    <div class="shoping__discount">
                        <h5>Discount Codes</h5>
                        <form action="#">
                            <input type="text" placeholder="Enter your coupon code">
                            <button type="submit" class="site-btn">APPLY COUPON</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shoping__checkout">
                    <h5>Cart Total</h5>
                    <ul>
                        <li>Subtotal <span class="subtotal">$<?php echo number_format($subtotal, 2); ?></span></li>
                        <li>Total <span class="total">$<?php echo number_format($total, 2); ?></span></li>
                    </ul>
                    <a href="#" class="primary-btn">PROCEED TO CHECKOUT</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Shoping Cart Section End -->

<!-- Add JavaScript for Quantity and Price Updates -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Function to update the total price for a specific item
        function updateTotalPrice(input) {
            const quantity = parseInt(input.val());
            const price = parseFloat(input.data('price'));
            const total = quantity * price;
            input.closest('tr').find('.shoping__cart__total').text('$' + total.toFixed(2));
        }

        // Function to update the subtotal and total
        function updateCartTotals() {
            let subtotal = 0;
            $('.shoping__cart__total').each(function () {
                subtotal += parseFloat($(this).text().replace('$', ''));
            });
            $('.subtotal span').text('$' + subtotal.toFixed(2));
            $('.total span').text('$' + subtotal.toFixed(2));
        }

        // Increment quantity
        $('.inc').on('click', function () {
            const input = $(this).siblings('.qty-input');
            let quantity = parseInt(input.val());
            quantity += 1;
            input.val(quantity);
            updateTotalPrice(input);
            updateCartTotals();
            updateCartInDatabase(input.data('cart-id'), quantity);
        });

        // Decrement quantity
        $('.dec').on('click', function () {
            const input = $(this).siblings('.qty-input');
            let quantity = parseInt(input.val());
            if (quantity > 1) {
                quantity -= 1;
                input.val(quantity);
                updateTotalPrice(input);
                updateCartTotals();
                updateCartInDatabase(input.data('cart-id'), quantity);
            }
        });

        // Function to update the cart in the database
        function updateCartInDatabase(cartId, quantity) {
            $.ajax({
                url: 'shoping-cart.php',
                type: 'POST',
                data: {
                    cart_id: cartId,
                    quantity: quantity
                },
                success: function (response) {
                    alert('Quantity updated successfully.');
                },
                error: function (xhr, status, error) {
                    alert('Error updating quantity. Please try again.');
                }
            });
        }

        // Handle delete action
        $('.delete-item').on('click', function (e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this item?')) {
                window.location.href = $(this).attr('href');
            }
        });
    });
</script>

<?php
include("components/footer.php");
?>