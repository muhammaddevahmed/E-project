<style>
.site-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  background-color: #cccccc;
}
</style>
<?php
include("components/header.php");


// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
  echo "<script>alert('Please Login First!'); window.location.href='login.php';</script>";
  exit();
}

// Check database connection
if (!isset($pdo)) {
  die("Database connection error");
}

// Set PDO error mode to exception
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Retrieve user information from the session
$userId = $_SESSION['user_id'];
$username = $_SESSION['full_name'];

// Initialize error array
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if cart is empty
  if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
      $errors['cart'] = "Your cart is empty. Please add products before checkout.";
  }
    
    // Validate and sanitize form data
    $firstName = validateInput('first_name', "/^[a-zA-Z ]{2,50}$/", "First name should contain only letters and spaces (2-50 characters)", $errors);
    $lastName = validateInput('last_name', "/^[a-zA-Z ]{2,50}$/", "Last name should contain only letters and spaces (2-50 characters)", $errors);
    $country = validateInput('country', "/^[a-zA-Z ]{2,50}$/", "Country should contain only letters and spaces (2-50 characters)", $errors);
    $address = validateInput('address', "/^[a-zA-Z0-9\s\-\.,#'\/]{5,100}$/", "Address should be 5-100 characters long", $errors);
    $city = validateInput('city', "/^[a-zA-Z ]{2,50}$/", "City should contain only letters and spaces (2-50 characters)", $errors);
    $state = validateInput('state', "/^[a-zA-Z ]{2,50}$/", "State should contain only letters and spaces (2-50 characters)", $errors);
    $postcode = validateInput('postcode', "/^[a-zA-Z0-9 \-]{3,10}$/", "Postcode should be 3-10 alphanumeric characters", $errors);
    $phone = validateInput('phone', "/^[0-9\+\-\(\)\s]{7,15}$/", "Phone number should be 7-15 digits with optional +-()", $errors);
    $email = validateInput('email', "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", "Please enter a valid email address", $errors);
    $paymentMethod = validateInput('payment_method', "/^(cash_on_delivery|check_payment|credit_card|paypal)$/", "Please select a valid payment method", $errors);
    $orderNotes = htmlspecialchars($_POST['order_notes'] ?? '');
    
    // Validate payment method specific fields
    if ($paymentMethod === 'credit_card') {
        $cardNumber = validateInput('card_number', "/^[0-9\s]{16,19}$/", "Card number should be 16-19 digits", $errors);
        $expiryDate = validateInput('expiry_date', "/^(0[1-9]|1[0-2])\/?([0-9]{2})$/", "Expiry date should be in MM/YY format", $errors);
        $cvv = validateInput('cvv', "/^[0-9]{3,4}$/", "CVV should be 3 or 4 digits", $errors);
    } elseif ($paymentMethod === 'check_payment') {
        $checkNumber = validateInput('check_number', "/^[a-zA-Z0-9]{5,20}$/", "Check number should be 5-20 alphanumeric characters", $errors);
        $bankName = validateInput('bank_name', "/^[a-zA-Z ]{2,50}$/", "Bank name should contain only letters and spaces (2-50 characters)", $errors);
    }
      // If no errors, process the order
      if (empty($errors)) {
        // Generate a more unique order ID
        $orderId = 'ORD' . date('YmdHis') . mt_rand(1000, 9999);
        
        // Calculate the total amount from the cart
        $subtotal = 0;
        foreach ($_SESSION['cart'] as $id => $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $total = $subtotal;

        try {
            // Begin transaction
            $pdo->beginTransaction();

            // 1. First check stock availability for all products
            foreach ($_SESSION['cart'] as $product_id => $item) {
                $quantity = $item['quantity'];
                
                $stockCheck = $pdo->prepare("SELECT stock_quantity FROM products WHERE product_id = ? FOR UPDATE");
                $stockCheck->execute([$product_id]);
                $currentStock = $stockCheck->fetchColumn();
                
                if ($currentStock === false) {
                    throw new PDOException("Product not found: $product_id");
                }
                
                if ($currentStock < $quantity) {
                    throw new PDOException("Not enough stock available for product ID: $product_id (Available: $currentStock, Requested: $quantity)");
                }
            }

            // 2. Insert payment data first to get payment_id
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
            if (!$paymentStmt) {
                throw new PDOException("Payment prepare failed: " . implode(" ", $pdo->errorInfo()));
            }

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
                $paymentStmt->bindParam(':card_number', $cardNumber);
                $paymentStmt->bindParam(':expiry_date', $expiryDate);
                $paymentStmt->bindParam(':cvv', $cvv);
                $paymentStmt->bindValue(':check_number', null);
                $paymentStmt->bindValue(':bank_name', null);
            } elseif ($paymentMethod === 'check_payment') {
                $paymentStmt->bindParam(':check_number', $checkNumber);
                $paymentStmt->bindParam(':bank_name', $bankName);
                $paymentStmt->bindValue(':card_number', null);
                $paymentStmt->bindValue(':expiry_date', null);
                $paymentStmt->bindValue(':cvv', null);
            } else {
                $paymentStmt->bindValue(':card_number', null);
                $paymentStmt->bindValue(':expiry_date', null);
                $paymentStmt->bindValue(':cvv', null);
                $paymentStmt->bindValue(':check_number', null);
                $paymentStmt->bindValue(':bank_name', null);
            }

            // Execute the payment statement
            $paymentStmt->execute();
            $payment_id = $pdo->lastInsertId();

            // Execute the payment statement
            if (!$paymentStmt->execute()) {
              throw new PDOException("Payment execute failed: " . implode(" ", $paymentStmt->errorInfo()));
          }
          $payment_id = $pdo->lastInsertId();

          // 3. Prepare order statement
          $orderSql = "INSERT INTO orders (
              order_id, delivery_type, product_id, order_number, 
              u_name, u_email, p_name, p_price, p_qty, 
              date_time, status, u_id, payment_id
          ) VALUES (
              :order_id, :delivery_type, :product_id, :order_number, 
              :u_name, :u_email, :p_name, :p_price, :p_qty, 
              NOW(), 'pending', :u_id, :payment_id
          )";

          $orderStmt = $pdo->prepare($orderSql);
          if (!$orderStmt) {
              throw new PDOException("Order prepare failed: " . implode(" ", $pdo->errorInfo()));
          }

          // Process each item in cart
          foreach ($_SESSION['cart'] as $product_id => $item) {
              $productName = $item['product_name'];
              $productPrice = $item['price'];
              $productQty = $item['quantity'];

              // Bind order parameters
              $orderStmt->bindParam(':order_id', $orderId);
              $orderStmt->bindParam(':delivery_type', $paymentMethod);
              $orderStmt->bindParam(':product_id', $product_id);
              $orderStmt->bindParam(':order_number', $orderId);
              $orderStmt->bindParam(':u_name', $username);
              $orderStmt->bindParam(':u_email', $email);
              $orderStmt->bindParam(':p_name', $productName);
              $orderStmt->bindParam(':p_price', $productPrice);
              $orderStmt->bindParam(':p_qty', $productQty);
              $orderStmt->bindParam(':u_id', $userId);
              $orderStmt->bindParam(':payment_id', $payment_id);

              // Execute order insertion
              if (!$orderStmt->execute()) {
                  throw new PDOException("Order execute failed: " . implode(" ", $orderStmt->errorInfo()));
              }

              // Update product stock
              $updateStock = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
              if (!$updateStock->execute([$productQty, $product_id])) {
                  throw new PDOException("Stock update failed: " . implode(" ", $updateStock->errorInfo()));
              }
          }

          // Commit the transaction
          $pdo->commit();

          // Clear the cart after successful order
          unset($_SESSION['cart']);

          // Set success session variables
          $_SESSION['order_success'] = true;
          $_SESSION['order_id'] = $orderId;
          $_SESSION['payment_id'] = $payment_id;

          // Redirect to confirmation page
          header("Location: order_confirmation.php");
          exit();

      } catch (PDOException $e) {
          // Rollback the transaction on error
          if ($pdo->inTransaction()) {
              $pdo->rollBack();
          }
          
          // Log detailed error for debugging
          error_log("Order processing error for user $userId: " . $e->getMessage());
          
          // Show detailed error message for debugging (remove in production)
          $errors['database'] = "Error processing your order: " . $e->getMessage();
          echo "<script>alert('Error processing your order: " . addslashes($e->getMessage()) . "');</script>";
      }
  }
}



// Helper function to validate input
function validateInput($fieldName, $pattern, $errorMessage, &$errors) {
// Check if the field exists in POST and get trimmed value
$value = isset($_POST[$fieldName]) ? trim($_POST[$fieldName]) : '';

// Check if the field is empty
if ($value === '') {
$errors[$fieldName] = "This field is required";
return '';
}

// Validate against pattern if provided
if ($pattern !== null && !preg_match($pattern, $value)) {
$errors[$fieldName] = $errorMessage;
return '';
}

// Return sanitized value
return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
<section class="breadcrumb-section set-bg"
  data-setbg="https://i.pinimg.com/736x/72/e6/21/72e62198095a1c36038869ddf05481f7.jpg">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <div class="breadcrumb__text">
          <h2>Checkout</h2>
          <div class="breadcrumb__option">
            <a href="./index.php">Home</a>
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
    <?php if (isset($errors['cart'])): ?>
    <div class="alert alert-danger">
      <?php echo $errors['cart']; ?>
    </div>
    <?php endif; ?>

    <div class="row">

    </div>
    <div class="checkout__form">
      <h4>Billing Details</h4>
      <form action="checkout.php" method="POST" id="checkoutForm"
        onsubmit="event.preventDefault(); validateAndSubmit();">

        <div class="row">
          <div class="col-lg-8 col-md-6">
            <div class="row">
              <div class="col-lg-6">
                <div class="checkout__input">
                  <p>First Name<span>*</span></p>
                  <input type="text" name="first_name"
                    value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>"
                    required>
                  <?php if (isset($errors['first_name'])): ?>
                  <small class="text-danger"><?php echo $errors['first_name']; ?></small>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="checkout__input">
                  <p>Last Name<span>*</span></p>
                  <input type="text" name="last_name"
                    value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>"
                    required>
                  <?php if (isset($errors['last_name'])): ?>
                  <small class="text-danger"><?php echo $errors['last_name']; ?></small>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="checkout__input">
              <p>Country<span>*</span></p>
              <input type="text" name="country"
                value="<?php echo isset($_POST['country']) ? htmlspecialchars($_POST['country']) : ''; ?>" required>
              <?php if (isset($errors['country'])): ?>
              <small class="text-danger"><?php echo $errors['country']; ?></small>
              <?php endif; ?>
            </div>
            <div class="checkout__input">
              <p>Address<span>*</span></p>
              <input type="text" name="address" placeholder="Street Address" class="checkout__input__add"
                value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required>
              <?php if (isset($errors['address'])): ?>
              <small class="text-danger"><?php echo $errors['address']; ?></small>
              <?php endif; ?>
            </div>
            <div class="checkout__input">
              <p>City<span>*</span></p>
              <input type="text" name="city"
                value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>" required>
              <?php if (isset($errors['city'])): ?>
              <small class="text-danger"><?php echo $errors['city']; ?></small>
              <?php endif; ?>
            </div>
            <div class="checkout__input">
              <p>State<span>*</span></p>
              <input type="text" name="state"
                value="<?php echo isset($_POST['state']) ? htmlspecialchars($_POST['state']) : ''; ?>" required>
              <?php if (isset($errors['state'])): ?>
              <small class="text-danger"><?php echo $errors['state']; ?></small>
              <?php endif; ?>
            </div>
            <div class="checkout__input">
              <p>Postcode / ZIP<span>*</span></p>
              <input type="text" name="postcode"
                value="<?php echo isset($_POST['postcode']) ? htmlspecialchars($_POST['postcode']) : ''; ?>" required>
              <?php if (isset($errors['postcode'])): ?>
              <small class="text-danger"><?php echo $errors['postcode']; ?></small>
              <?php endif; ?>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="checkout__input">
                  <p>Phone<span>*</span></p>
                  <input type="text" name="phone"
                    value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
                  <?php if (isset($errors['phone'])): ?>
                  <small class="text-danger"><?php echo $errors['phone']; ?></small>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="checkout__input">
                  <p>Email<span>*</span></p>
                  <input type="text" name="email"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                  <?php if (isset($errors['email'])): ?>
                  <small class="text-danger"><?php echo $errors['email']; ?></small>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="checkout__input">
              <p>Order notes</p>
              <input type="text" name="order_notes"
                placeholder="Notes about your order, e.g. special notes for delivery."
                value="<?php echo isset($_POST['order_notes']) ? htmlspecialchars($_POST['order_notes']) : ''; ?>">
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
              <div class="checkout__order__subtotal">Subtotal <span>$<?php echo number_format($subtotal, 2); ?></span>
              </div>
              <div class="checkout__order__total">Total <span>$<?php echo number_format($total, 2); ?></span></div>

              <!-- Payment Methods -->
              <div class="checkout__payment__methods">
                <h5>Payment Methods</h5>
                <?php if (isset($errors['payment_method'])): ?>
                <small class="text-danger"><?php echo $errors['payment_method']; ?></small>
                <?php endif; ?>

                <div class="checkout__input__checkbox">
                  <label for="cash-on-delivery">
                    <i class="fa fa-money-bill-wave"></i> Cash on Delivery
                    <input type="radio" id="cash-on-delivery" name="payment_method" value="cash_on_delivery"
                      <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'cash_on_delivery') ? 'checked' : ''; ?>
                      required>
                    <span class="checkmark"></span>
                  </label>
                </div>
                <div class="checkout__input__checkbox">
                  <label for="check-payment">
                    <i class="fa fa-money-check"></i> Check Payment
                    <input type="radio" id="check-payment" name="payment_method" value="check_payment"
                      <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'check_payment') ? 'checked' : ''; ?>>
                    <span class="checkmark"></span>
                  </label>
                </div>
                <div class="checkout__input__checkbox">
                  <label for="credit-card">
                    <i class="fa fa-credit-card"></i> Credit/Debit Card
                    <input type="radio" id="credit-card" name="payment_method" value="credit_card"
                      <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'credit_card') ? 'checked' : ''; ?>>
                    <span class="checkmark"></span>
                  </label>
                </div>
                <div class="checkout__input__checkbox">
                  <label for="paypal">
                    <i class="fa fa-paypal"></i> PayPal
                    <input type="radio" id="paypal" name="payment_method" value="paypal"
                      <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'paypal') ? 'checked' : ''; ?>>
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
                  <input type="text" name="check_number" placeholder="Enter check number"
                    value="<?php echo isset($_POST['check_number']) ? htmlspecialchars($_POST['check_number']) : ''; ?>">
                  <?php if (isset($errors['check_number'])): ?>
                  <small class="text-danger"><?php echo $errors['check_number']; ?></small>
                  <?php endif; ?>
                </div>
                <div class="checkout__input">
                  <p>Bank Name<span>*</span></p>
                  <input type="text" name="bank_name" placeholder="Enter bank name"
                    value="<?php echo isset($_POST['bank_name']) ? htmlspecialchars($_POST['bank_name']) : ''; ?>">
                  <?php if (isset($errors['bank_name'])): ?>
                  <small class="text-danger"><?php echo $errors['bank_name']; ?></small>
                  <?php endif; ?>
                </div>
              </div>
              <div id="card-payment-form" class="payment-form" style="display: none;">
                <div class="checkout__input">
                  <p>Card Number<span>*</span></p>
                  <input type="text" name="card_number" placeholder="1234 5678 9012 3456"
                    value="<?php echo isset($_POST['card_number']) ? htmlspecialchars($_POST['card_number']) : ''; ?>">
                  <?php if (isset($errors['card_number'])): ?>
                  <small class="text-danger"><?php echo $errors['card_number']; ?></small>
                  <?php endif; ?>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="checkout__input">
                      <p>Expiration Date<span>*</span></p>
                      <input type="text" name="expiry_date" placeholder="MM/YY"
                        value="<?php echo isset($_POST['expiry_date']) ? htmlspecialchars($_POST['expiry_date']) : ''; ?>">
                      <?php if (isset($errors['expiry_date'])): ?>
                      <small class="text-danger"><?php echo $errors['expiry_date']; ?></small>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="checkout__input">
                      <p>CVV<span>*</span></p>
                      <input type="text" name="cvv" placeholder="123"
                        value="<?php echo isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : ''; ?>">
                      <?php if (isset($errors['cvv'])): ?>
                      <small class="text-danger"><?php echo $errors['cvv']; ?></small>
                      <?php endif; ?>
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

<?php include("components/footer.php"); ?><script>
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

// Initialize payment forms on page load
document.addEventListener('DOMContentLoaded', function() {
  const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
  if (selectedPayment) {
    selectedPayment.dispatchEvent(new Event('change'));
  }
  checkCartStatus();

  // Add form submission handler
  document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    validateAndSubmit();
  });
});

// Function to check cart status and disable button if empty
function checkCartStatus() {
  const placeOrderBtn = document.querySelector('.site-btn');
  <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
  placeOrderBtn.disabled = true;
  placeOrderBtn.title = "Your cart is empty. Please add products before checkout.";
  placeOrderBtn.style.opacity = "0.7";
  placeOrderBtn.style.cursor = "not-allowed";
  <?php else: ?>
  placeOrderBtn.disabled = false;
  placeOrderBtn.title = "";
  placeOrderBtn.style.opacity = "1";
  placeOrderBtn.style.cursor = "pointer";
  <?php endif; ?>
}

// Helper function to display error messages
function showError(fieldName, message) {
  let errorElement = document.querySelector(`[name="${fieldName}"] + .text-danger`);

  if (!errorElement) {
    const inputElement = document.querySelector(`[name="${fieldName}"]`);
    if (inputElement) {
      errorElement = document.createElement('small');
      errorElement.className = 'text-danger error-message';
      inputElement.parentNode.appendChild(errorElement);
    }
  }

  if (errorElement) {
    errorElement.textContent = message;
    errorElement.style.display = 'block';
  }

  if (fieldName === 'payment_method') {
    const paymentError = document.querySelector('.checkout__payment__methods .text-danger');
    if (paymentError) {
      paymentError.textContent = message;
      paymentError.style.display = 'block';
    }
  }
}

// Main form validation and submission function
function validateAndSubmit() {
  // Clear previous error messages
  document.querySelectorAll('.error-message').forEach(el => el.remove());
  document.querySelectorAll('.text-danger').forEach(el => el.style.display = 'none');

  // Validate all fields
  let isValid = true;

  <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
  showError('cart', 'Your cart is empty. Please add products before checkout.');
  isValid = false;
  <?php endif; ?>

  // Validate required fields
  const requiredFields = [
    'first_name', 'last_name', 'country', 'address', 'city',
    'state', 'postcode', 'phone', 'email', 'payment_method'
  ];

  requiredFields.forEach(field => {
    const value = document.querySelector(`[name="${field}"]`)?.value.trim();
    if (!value) {
      showError(field, 'This field is required');
      isValid = false;
    }
  });

  // Validate field patterns
  const fieldPatterns = {
    'first_name': {
      pattern: /^[a-zA-Z ]{2,50}$/,
      message: 'First name should contain only letters and spaces (2-50 characters)'
    },
    'last_name': {
      pattern: /^[a-zA-Z ]{2,50}$/,
      message: 'Last name should contain only letters and spaces (2-50 characters)'
    },
    'country': {
      pattern: /^[a-zA-Z ]{2,50}$/,
      message: 'Country should contain only letters and spaces (2-50 characters)'
    },
    'address': {
      pattern: /^[a-zA-Z0-9\s\-\.,#'\/]{5,100}$/,
      message: 'Address should be 5-100 characters long'
    },
    'city': {
      pattern: /^[a-zA-Z ]{2,50}$/,
      message: 'City should contain only letters and spaces (2-50 characters)'
    },
    'state': {
      pattern: /^[a-zA-Z ]{2,50}$/,
      message: 'State should contain only letters and spaces (2-50 characters)'
    },
    'postcode': {
      pattern: /^[a-zA-Z0-9 \-]{3,10}$/,
      message: 'Postcode should be 3-10 alphanumeric characters'
    },
    'phone': {
      pattern: /^[0-9\+\-\(\)\s]{7,15}$/,
      message: 'Phone number should be 7-15 digits with optional +-()'
    },
    'email': {
      pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
      message: 'Please enter a valid email address'
    }
  };

  for (const [field, validation] of Object.entries(fieldPatterns)) {
    const input = document.querySelector(`[name="${field}"]`);
    if (input && input.value.trim() && !validation.pattern.test(input.value.trim())) {
      showError(field, validation.message);
      isValid = false;
    }
  }

  // Validate payment method specific fields
  const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
  if (!paymentMethod) {
    showError('payment_method', 'Please select a payment method');
    isValid = false;
  } else {
    if (paymentMethod.value === 'credit_card') {
      const cardNumber = document.querySelector('input[name="card_number"]')?.value.trim();
      const expiryDate = document.querySelector('input[name="expiry_date"]')?.value.trim();
      const cvv = document.querySelector('input[name="cvv"]')?.value.trim();

      if (!cardNumber || !/^[0-9\s]{16,19}$/.test(cardNumber)) {
        showError('card_number', 'Card number should be 16-19 digits');
        isValid = false;
      }

      if (!expiryDate || !/^(0[1-9]|1[0-2])\/?([0-9]{2})$/.test(expiryDate)) {
        showError('expiry_date', 'Expiry date should be in MM/YY format');
        isValid = false;
      }

      if (!cvv || !/^[0-9]{3,4}$/.test(cvv)) {
        showError('cvv', 'CVV should be 3 or 4 digits');
        isValid = false;
      }
    } else if (paymentMethod.value === 'check_payment') {
      const checkNumber = document.querySelector('input[name="check_number"]')?.value.trim();
      const bankName = document.querySelector('input[name="bank_name"]')?.value.trim();

      if (!checkNumber || !/^[a-zA-Z0-9]{5,20}$/.test(checkNumber)) {
        showError('check_number', 'Check number should be 5-20 alphanumeric characters');
        isValid = false;
      }

      if (!bankName || !/^[a-zA-Z ]{2,50}$/.test(bankName)) {
        showError('bank_name', 'Bank name should contain only letters and spaces (2-50 characters)');
        isValid = false;
      }
    }
  }

  // If validation passed, submit the form
  if (isValid) {
    // Show loading state
    const submitBtn = document.querySelector('.site-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Processing... <i class="fa fa-spinner fa-spin"></i>';

    // Directly submit the form if all validations pass
    document.getElementById('checkoutForm').submit();
  }
}
</script>