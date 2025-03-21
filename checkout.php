<?php
session_start(); // Start the session
include("components/header.php");



// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Form submitted!<br>"; // Debugging message
    print_r($_POST); // Debugging: Display form data

    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postcode = $_POST['postcode'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $payment_method = $_POST['payment_method'];
    $order_notes = $_POST['order_notes'];

    // Calculate subtotal and total
    $subtotal = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            if (isset($item['price']) && isset($item['quantity'])) {
                $subtotal += $item['price'] * $item['quantity'];
            }
        }
    }
    $total = $subtotal; // Assuming no tax or shipping for now

    // Insert order details into the database
    include("php/db_connection.php"); // Include your database connection file

    // Generate a unique 8-digit order number
    $order_number = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

    // Insert into the `order` table
    $stmt = $conn->prepare("INSERT INTO `orders` (
        order_id, delivery_type, product_id, order_number, u_name, u_email, p_name, p_price, p_qty, date_time, status, u_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)");

    foreach ($_SESSION['cart'] as $product_id => $item) {
        // Delivery type (1 digit)
        $delivery_type = '1'; // Example: '1' for standard delivery

        // Generate 16-digit order ID
        $order_id = $delivery_type . str_pad($product_id, 7, '0', STR_PAD_LEFT) . $order_number;

        // User details
        $u_name = "$first_name $last_name";
        $u_email = $email;
        $p_name = $item['product_name'];
        $p_price = $item['price'];
        $p_qty = $item['quantity'];
        $status = 'pending'; // Default status
        $u_id = $_SESSION['user_id']; // Logged-in user ID

        // Bind parameters and execute the query
        $stmt->bind_param(
            "sssssssiisi",
            $order_id,
            $delivery_type,
            $product_id,
            $order_number,
            $u_name,
            $u_email,
            $p_name,
            $p_price,
            $p_qty,
            $status,
            $u_id
        );

        if (!$stmt->execute()) {
            die("Error inserting order: " . $stmt->error);
        }
    }

    // Clear the cart after placing the order
    unset($_SESSION['cart']);

    // Redirect to a thank-you page or order confirmation page
    header("Location: order_confirmation.php");
    exit();
}

// Calculate subtotal and total for display
$subtotal = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['price']) && isset($item['quantity'])) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }
}
$total = $subtotal; // Assuming no tax or shipping for now
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        .payment-form {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #e5e5e5;
            border-radius: 5px;
            background-color: #f9f9f9;
            width: 40%;
        }
        .payment-form p {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 15px;
        }
        .payment-form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #e5e5e5;
            border-radius: 3px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Checkout</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.html">Home</a>
                            <span>Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h6><span class="icon_tag_alt"></span> Have a coupon? <a href="#">Click here</a> to enter your code</h6>
                </div>
            </div>
            <div class="checkout__form">
                <h4>Billing Details</h4>
                <form action="checkout.php" method="POST">
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>First Name<span>*</span></p>
                                        <input type="text" name="first_name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Last Name<span>*</span></p>
                                        <input type="text" name="last_name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__input">
                                <p>Country<span>*</span></p>
                                <input type="text" name="country" required>
                            </div>
                            <div class="checkout__input">
                                <p>Address<span>*</span></p>
                                <input type="text" name="address" placeholder="Street Address" class="checkout__input__add" required>
                                <input type="text" name="apartment" placeholder="Apartment, suite, unit (optional)">
                            </div>
                            <div class="checkout__input">
                                <p>Town/City<span>*</span></p>
                                <input type="text" name="city" required>
                            </div>
                            <div class="checkout__input">
                                <p>State<span>*</span></p>
                                <input type="text" name="state" required>
                            </div>
                            <div class="checkout__input">
                                <p>Postcode / ZIP<span>*</span></p>
                                <input type="text" name="postcode" required>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Phone<span>*</span></p>
                                        <input type="text" name="phone" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Email<span>*</span></p>
                                        <input type="email" name="email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__input">
                                <p>Order notes<span>*</span></p>
                                <input type="text" name="order_notes" placeholder="Notes about your order, e.g. special notes for delivery.">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4>Your Order</h4>
                                <div class="checkout__order__products">Products <span>Total</span></div>
                                <ul>
                                    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                                        <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                                            <?php if (isset($item['product_name']) && isset($item['price']) && isset($item['quantity'])): ?>
                                                <li>
                                                    <?php echo $item['product_name']; ?> (Qty: <?php echo $item['quantity']; ?>)
                                                    <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li>Your cart is empty.</li>
                                    <?php endif; ?>
                                </ul>
                                <div class="checkout__order__subtotal">Subtotal <span>$<?php echo number_format($subtotal, 2); ?></span></div>
                                <div class="checkout__order__total">Total <span>$<?php echo number_format($total, 2); ?></span></div>

                                <!-- Payment Methods -->
                                <div class="checkout__payment__methods">
                                    <h5>Payment Methods</h5>
                                    <div class="checkout__input__checkbox">
                                        <label for="cash-on-delivery">
                                            <i class="fa fa-money-bill-wave"></i> Cash on Delivery
                                            <input type="radio" id="cash-on-delivery" name="payment_method" value="cash_on_delivery" required>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="checkout__input__checkbox">
                                        <label for="check-payment">
                                            <i class="fa fa-money-check"></i> Check Payment
                                            <input type="radio" id="check-payment" name="payment_method" value="check_payment">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="checkout__input__checkbox">
                                        <label for="credit-card">
                                            <i class="fa fa-credit-card"></i> Credit/Debit Card
                                            <input type="radio" id="credit-card" name="payment_method" value="credit_card">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="checkout__input__checkbox">
                                        <label for="paypal">
                                            <i class="fa fa-paypal"></i> PayPal
                                            <input type="radio" id="paypal" name="payment_method" value="paypal">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="site-btn">PLACE ORDER</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->

    <?php include("components/footer.php"); ?>
</body>
</html>