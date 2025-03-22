<?php

include("components/header.php");


// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please Login First!'); window.location.href='login.php';</script>";
    exit();
}

// Retrieve user information from the session
$userId = $_SESSION['user_id'];
$username = $_SESSION['full_name'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $firstName = htmlspecialchars($_POST['first_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $country = htmlspecialchars($_POST['country']);
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $state = htmlspecialchars($_POST['state']);
    $postcode = htmlspecialchars($_POST['postcode']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $paymentMethod = htmlspecialchars($_POST['payment_method']);
    $orderNotes = htmlspecialchars($_POST['order_notes']);

    // Generate a unique order ID
    $orderId = uniqid();

    // Calculate the total amount from the cart
    $subtotal = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $item) {
            if (isset($item['price']) && isset($item['quantity'])) {
                $subtotal += $item['price'] * $item['quantity'];
            }
        }
    }
    $total = $subtotal;

    // Insert the order into the database
    $sql = "INSERT INTO orders (order_id, delivery_type, product_id, order_number, u_name, u_email, p_name, p_price, p_qty, date_time, status, u_id)
            VALUES (:order_id, :delivery_type, :product_id, :order_number, :u_name, :u_email, :p_name, :p_price, :p_qty, NOW(), 'pending', :u_id)";

    try {
        $stmt = $pdo->prepare($sql);

        // Loop through the cart items and insert each product as a separate order
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $productName = $item['product_name'];
            $productPrice = $item['price'];
            $productQty = $item['quantity'];

            // Bind parameters
            $stmt->bindParam(':order_id', $orderId);
            $stmt->bindParam(':delivery_type', $paymentMethod);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':order_number', $orderId);
            $stmt->bindParam(':u_name', $username);
            $stmt->bindParam(':u_email', $email);
            $stmt->bindParam(':p_name', $productName);
            $stmt->bindParam(':p_price', $productPrice);
            $stmt->bindParam(':p_qty', $productQty);
            $stmt->bindParam(':u_id', $userId);

            // Execute the statement
            $stmt->execute();
        }

        // Insert payment data into the payments table
        $paymentSql = "INSERT INTO payments (
             payment_method, amount, payment_status, 
            first_name, last_name, country, address, city, state, postcode, 
            phone, email, order_notes, card_number, expiry_date, cvv, check_number, bank_name
        ) VALUES (
             :payment_method, :amount, 'pending', 
            :first_name, :last_name, :country, :address, :city, :state, :postcode, 
            :phone, :email, :order_notes, :card_number, :expiry_date, :cvv, :check_number, :bank_name
        )";

        $paymentStmt = $pdo->prepare($paymentSql);

        // Bind payment parameters

        $paymentStmt->bindParam(':payment_method', $paymentMethod);
        $paymentStmt->bindParam(':amount', $total);
        $paymentStmt->bindParam(':first_name', $firstName);
        $paymentStmt->bindParam(':last_name', $lastName);
        $paymentStmt->bindParam(':country', $country);
        $paymentStmt->bindParam(':address', $address);
        $paymentStmt->bindParam(':city', $city);
        $paymentStmt->bindParam(':state', $state);
        $paymentStmt->bindParam(':postcode', $postcode);
        $paymentStmt->bindParam(':phone', $phone);
        $paymentStmt->bindParam(':email', $email);
        $paymentStmt->bindParam(':order_notes', $orderNotes);

        // Additional payment details based on the payment method
        if ($paymentMethod === 'credit_card') {
            $cardNumber = htmlspecialchars($_POST['card_number']);
            $expiryDate = htmlspecialchars($_POST['expiry_date']);
            $cvv = htmlspecialchars($_POST['cvv']);
            $paymentStmt->bindParam(':card_number', $cardNumber);
            $paymentStmt->bindParam(':expiry_date', $expiryDate);
            $paymentStmt->bindParam(':cvv', $cvv);
            $paymentStmt->bindValue(':check_number', null);
            $paymentStmt->bindValue(':bank_name', null);
        } elseif ($paymentMethod === 'check_payment') {
            $checkNumber = htmlspecialchars($_POST['check_number']);
            $bankName = htmlspecialchars($_POST['bank_name']);
            $paymentStmt->bindValue(':card_number', null);
            $paymentStmt->bindValue(':expiry_date', null);
            $paymentStmt->bindValue(':cvv', null);
            $paymentStmt->bindParam(':check_number', $checkNumber);
            $paymentStmt->bindParam(':bank_name', $bankName);
        } else {
            // For cash_on_delivery and other methods, set payment details to null
            $paymentStmt->bindValue(':card_number', null);
            $paymentStmt->bindValue(':expiry_date', null);
            $paymentStmt->bindValue(':cvv', null);
            $paymentStmt->bindValue(':check_number', null);
            $paymentStmt->bindValue(':bank_name', null);
        }

        // Execute the payment statement
        $paymentStmt->execute();

        // Clear the cart after the order is placed
        unset($_SESSION['cart']);

        // Show a JavaScript alert
        echo "<script>alert('Your order has been placed. Thank you for shopping with us!');</script>";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // Debug
    }
}

// Calculate subtotal and total for display
$subtotal = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $item) {
        if (isset($item['price']) && isset($item['quantity'])) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }
}
$total = $subtotal; // Assuming no tax or shipping for now

?>

    

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
                                
                            </div>
                            <div class="checkout__input">
                                <p>Town/City<span>*</span></p>
                                <input type="text" name="city" required>
                            </div>
                            <div class="checkout__input">
                                <p>Country/State<span>*</span></p>
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
                                        <input type="text" name="email" required>
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

                                <!-- Payment Forms -->
                                <div id="cash-on-delivery-message" class="payment-form" style="display: none;">
                                    <p>Pay with cash when your order is delivered.</p>
                                </div>
                                <div id="check-payment-form" class="payment-form" style="display: none;">
                                    <div class="checkout__input">
                                        <p>Check Number<span>*</span></p>
                                        <input type="text" name="check_number" placeholder="Enter check number">
                                    </div>
                                    <div class="checkout__input">
                                        <p>Bank Name<span>*</span></p>
                                        <input type="text" name="bank_name" placeholder="Enter bank name">
                                    </div>
                                </div>
                                <div id="card-payment-form" class="payment-form" style="display: none;">
                                    <div class="checkout__input">
                                        <p>Card Number<span>*</span></p>
                                        <input type="text" name="card_number" placeholder="1234 5678 9012 3456">
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="checkout__input">
                                                <p>Expiration Date<span>*</span></p>
                                                <input type="text" name="expiry_date" placeholder="MM/YY">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="checkout__input">
                                                <p>CVV<span>*</span></p>
                                                <input type="text" name="cvv" placeholder="123">
                                            </div>
                                        </div>
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

    <script>
        // Show/hide payment forms based on selected payment method
        document.querySelectorAll('input[name="payment_method"]').forEach((input) => {
            input.addEventListener('change', function() {
                document.querySelectorAll('.payment-form').forEach((form) => {
                    form.style.display = 'none';
                });

                if (this.value === 'cash_on_delivery') {
                    document.getElementById('cash-on-delivery-message').style.display = 'block';
                } else if (this.value === 'check_payment') {
                    document.getElementById('check-payment-form').style.display = 'block';
                } else if (this.value === 'credit_card') {
                    document.getElementById('card-payment-form').style.display = 'block';
                }
            });
        });
    </script>
