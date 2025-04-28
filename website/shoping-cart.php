<?php
include("components/header.php");

// Check user type
$user_type = $_SESSION['user_type'] ?? '';

// Load cart for logged-in users
if (isset($_SESSION['user_id']) && !isset($_SESSION['cart'])) {
    try {
        $stmt = $pdo->prepare("SELECT c.product_id, c.quantity, c.price, c.product_name, c.image_path 
                              FROM cart c 
                              WHERE c.user_id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $_SESSION['cart'] = [];
        foreach ($cart_items as $item) {
            // Ensure image_path is valid and prepend base path if necessary
            $image_path = $item['image_path'];
            if (!empty($image_path)) {
                // Adjust path to be absolute from web root (e.g., /images/path/to/image.jpg)
                if (strpos($image_path, '/') !== 0) {
                    $image_path = '/images/' . ltrim($image_path, '/');
                }
            } else {
                // Fallback image if path is empty
                $image_path = '/images/default-product.jpg';
            }

            $_SESSION['cart'][$item['product_id']] = [
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'product_name' => $item['product_name'],
                'image_path' => $image_path
            ];
        }
    } catch (PDOException $e) {
        // Silently handle error
    }
}

// Handle stock check via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'check_stock') {
    $product_id = $_POST['product_id'];

    try {
        $query = "SELECT stock_quantity FROM products WHERE product_id = :product_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['product_id' => $product_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo json_encode(['stock_quantity' => (int)$row['stock_quantity']]);
        } else {
            echo json_encode(['stock_quantity' => 0]);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error']);
    }
    exit();
}

// Handle AJAX cart update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_cart') {
    $id = $_POST['id'];
    $quantity = (int)$_POST['quantity'];

    try {
        $query = "SELECT stock_quantity, price, product_name, image_path FROM products WHERE product_id = :product_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['product_id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $stock_quantity = $row ? (int)$row['stock_quantity'] : 0;
        if ($quantity > $stock_quantity) {
            echo json_encode(['error' => "Quantity exceeds available stock. Only $stock_quantity items available."]);
            exit();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        // Ensure image_path is valid
        $image_path = $row['image_path'];
        if (!empty($image_path)) {
            if (strpos($image_path, '/') !== 0) {
                $image_path = '/images/' . ltrim($image_path, '/');
            }
        } else {
            $image_path = '/images/default-product.jpg';
        }

        $_SESSION['cart'][$id]['quantity'] = $quantity;
        $_SESSION['cart'][$id]['price'] = $row['price'];
        $_SESSION['cart'][$id]['product_name'] = $row['product_name'];
        $_SESSION['cart'][$id]['image_path'] = $image_path;

        // Update cart in database for logged-in users
        if (isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare("
                INSERT INTO cart (user_id, product_id, quantity, price, product_name, image_path) 
                VALUES (:user_id, :product_id, :quantity, :price, :product_name, :image_path) 
                ON DUPLICATE KEY UPDATE 
                    quantity = :quantity, 
                    price = :price, 
                    product_name = :product_name, 
                    image_path = :image_path
            ");
            $stmt->execute([
                'user_id' => $_SESSION['user_id'],
                'product_id' => $id,
                'quantity' => $quantity,
                'price' => $row['price'],
                'product_name' => $row['product_name'],
                'image_path' => $image_path
            ]);
        }

        echo json_encode(['success' => 'Cart updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error']);
    }
    exit();
}

// Handle promo code request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'request_promo') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'You must be logged in to request a promo code']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM promo_code_requests WHERE user_id = :user_id AND status = 'pending'");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['error' => 'You already have a pending promo code request']);
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO promo_code_requests (user_id) VALUES (:user_id)");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);

        echo json_encode(['success' => 'Promo code request submitted. Admin will review your request.']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error']);
    }
    exit();
}

// Handle promo code application
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'apply_promo') {
    $promo_code = trim($_POST['promo_code']);
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'You must be logged in to use promo codes']);
        exit();
    }

    try {
        // Check if the promo code exists and is already used
        $query = "SELECT * FROM promo_codes 
                 WHERE code = :code 
                 AND user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'code' => $promo_code,
            'user_id' => $_SESSION['user_id']
        ]);
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($promo && $promo['is_used'] === true) {
            echo json_encode(['error' => 'Promo code is already used. Please request a new one.']);
            exit();
        }

        // Check if the promo code is valid and not expired
        $query = "SELECT * FROM promo_codes 
                 WHERE code = :code 
                 AND user_id = :user_id 
                 AND is_used = FALSE 
                 AND (expires_at IS NULL OR expires_at > NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'code' => $promo_code,
            'user_id' => $_SESSION['user_id']
        ]);
        
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($promo) {
            $_SESSION['promo_applied'] = true;
            $_SESSION['promo_code'] = $promo['code'];
            $_SESSION['promo_discount'] = $promo['discount_percent'];
            
            $stmt = $pdo->prepare("UPDATE promo_codes SET is_used = TRUE WHERE code_id = :code_id");
            $stmt->execute(['code_id' => $promo['code_id']]);
            
            echo json_encode([
                'success' => 'Promo code applied successfully! ' . $promo['discount_percent'] . '% discount added.',
                'discount_percent' => $promo['discount_percent']
            ]);
        } else {
            echo json_encode(['error' => 'Invalid or expired promo code. Please try again.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error']);
    }
    exit();
}

// Handle promo code removal
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'remove_promo') {
    unset($_SESSION['promo_applied']);
    unset($_SESSION['promo_code']);
    unset($_SESSION['promo_discount']);
    echo json_encode(['success' => 'Promo code removed successfully.']);
    exit();
}

// Clear cart if requested
if (isset($_GET['ch'])) {
    unset($_SESSION['cart']);
    unset($_SESSION['promo_applied']);
    unset($_SESSION['promo_code']);
    unset($_SESSION['promo_discount']);
    
    // Clear cart from database for logged-in users
    if (isset($_SESSION['user_id'])) {
        try {
            $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $_SESSION['user_id']]);
        } catch (PDOException $e) {
            // Silently handle error
        }
    }
}

// Calculate subtotal and total
$subtotal = 0;
$cart_empty = true;
$discount_amount = 0;
$has_promo_request = false;
$has_available_promo = false;
$latest_request_status = null;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_empty = false;
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['price']) && isset($item['quantity'])) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }
}

// Check for promo request or available promo codes
if (isset($_SESSION['user_id'])) {
    try {
        // Check for the latest promo code request status
        $stmt = $pdo->prepare("SELECT status FROM promo_code_requests WHERE user_id = :user_id ORDER BY request_date DESC LIMIT 1");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $latest_request = $stmt->fetch(PDO::FETCH_ASSOC);
        $latest_request_status = $latest_request ? $latest_request['status'] : null;

        // Check for pending promo code request
        $stmt = $pdo->prepare("SELECT * FROM promo_code_requests WHERE user_id = :user_id AND status = 'pending'");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $has_promo_request = $stmt->rowCount() > 0;

        // Check for available promo codes
        $stmt = $pdo->prepare("SELECT * FROM promo_codes 
                              WHERE user_id = :user_id 
                              AND is_used = FALSE 
                              AND (expires_at IS NULL OR expires_at > NOW())");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $available_promos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $has_available_promo = count($available_promos) > 0;
        
        // If there's exactly one available promo code, apply it automatically
        if (count($available_promos) === 1 && !isset($_SESSION['promo_applied'])) {
            $_SESSION['promo_applied'] = true;
            $_SESSION['promo_code'] = $available_promos[0]['code'];
            $_SESSION['promo_discount'] = $available_promos[0]['discount_percent'];
            
            // Mark the promo code as used
            $stmt = $pdo->prepare("UPDATE promo_codes SET is_used = TRUE WHERE code_id = :code_id");
            $stmt->execute(['code_id' => $available_promos[0]['code_id']]);
            
            // Recalculate totals
            $discount_amount = $subtotal * ($_SESSION['promo_discount'] / 100);
            $total = $subtotal - $discount_amount;
        }
    } catch (PDOException $e) {
        // Silently handle error
    }
}

// Apply discount if promo code is active
if (isset($_SESSION['promo_applied']) && $_SESSION['promo_applied'] === true) {
    $discount_amount = $subtotal * ($_SESSION['promo_discount'] / 100);
}

$total = $subtotal - $discount_amount;
?>

<style>
/* Responsive styling with Bootstrap integration */
.container {
  max-width: 1200px;
  padding: clamp(1rem, 3vw, 2rem);
  margin: 0 auto;
}

.alert {
  padding: clamp(0.5rem, 1vw, 0.75rem) clamp(0.75rem, 1.5vw, 1rem);
  margin-bottom: 1rem;
  border-radius: 0.25rem;
  font-size: clamp(0.75rem, 1vw, 0.85rem);
}

.alert-success {
  background-color: #dff0d8;
  color: #3c763d;
  border: 1px solid #d6e9c6;
}

.shoping__cart__table {
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.shoping__cart__table table {
  width: 100%;
  border-collapse: collapse;
  min-width: 600px;
}

.shoping__cart__table th,
.shoping__cart__table td {
  padding: clamp(0.5rem, 1vw, 0.75rem);
  text-align: left;
  border-bottom: 1px solid #ddd;
  vertical-align: middle;
  font-size: clamp(0.75rem, 1vw, 0.85rem);
}

.shoping__cart__table th {
  background-color: #7fad39;
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  font-size: clamp(0.7rem, 0.9vw, 0.8rem);
}

.shoping__cart__table img {
  width: clamp(60px, 10vw, 80px);
  height: auto;
  border-radius: 5px;
}

.shoping__cart__table h5 {
  margin: 0.5rem 0 0;
  font-size: clamp(0.85rem, 1.2vw, 1rem);
}

.quantity {
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

.qty-btn {
  padding: clamp(0.2rem, 0.5vw, 0.3rem) clamp(0.4rem, 0.8vw, 0.6rem);
  font-size: clamp(0.7rem, 1vw, 0.8rem);
  border: none;
  border-radius: 3px;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.dec {
  background: #d02323;
  color: white;
}

.inc {
  background: #7fad39;
  color: white;
}

.qty-input {
  width: clamp(40px, 8vw, 50px);
  text-align: center;
  border: 1px solid #ccc;
  font-size: clamp(0.7rem, 1vw, 0.8rem);
  margin: 0;
  padding: clamp(0.2rem, 0.5vw, 0.3rem);
  border-radius: 3px;
}

.btn-danger {
  padding: clamp(0.2rem, 0.5vw, 0.3rem) clamp(0.4rem, 0.8vw, 0.6rem);
  font-size: clamp(0.7rem, 1vw, 0.8rem);
  background: #d02323;
  color: white;
  border: none;
  border-radius: 3px;
}

.btn-danger:hover {
  background: #b01e1e;
}

.shoping__checkout {
  background: #f8f9fa;
  padding: clamp(1rem, 2vw, 1.5rem);
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  margin-top: 1.5rem;
}

.shoping__checkout h5 {
  font-size: clamp(1rem, 1.5vw, 1.2rem);
  color: #7fad39;
  margin-bottom: 1rem;
}

.shoping__checkout ul {
  list-style: none;
  padding: 0;
  margin-bottom: 1rem;
}

.shoping__checkout ul li {
  display: flex;
  justify-content: space-between;
  font-size: clamp(0.8rem, 1.2vw, 0.9rem);
  margin-bottom: 0.5rem;
}

.promo-section {
  margin-top: 1rem;
}

.promo-section input {
  padding: clamp(0.3rem, 0.8vw, 0.5rem);
  border: 1px solid #ccc;
  border-radius: 3px;
  width: clamp(120px, 20vw, 150px);
  font-size: clamp(0.75rem, 1vw, 0.85rem);
  margin-right: 0.5rem;
}

.promo-section button {
  padding: clamp(0.3rem, 0.8vw, 0.5rem) clamp(0.5rem, 1vw, 0.75rem);
  font-size: clamp(0.7rem, 1vw, 0.8rem);
  background: #7fad39;
  color: white;
  border: none;
  border-radius: 3px;
  cursor: pointer;
}

.promo-section button:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.promo-section #remove_promo {
  background: #d02323;
}

.promo-message {
  margin-top: 0.5rem;
  font-size: clamp(0.7rem, 1vw, 0.8rem);
}

.primary-btn {
  display: inline-block;
  padding: clamp(0.5rem, 1vw, 0.75rem) clamp(1rem, 2vw, 1.5rem);
  background: #7fad39;
  color: white;
  text-decoration: none;
  border-radius: 0.25rem;
  font-size: clamp(0.8rem, 1.2vw, 0.9rem);
  margin-top: 1rem;
}

.primary-btn:hover {
  background: #6b9a31;
}

/* Scrollbar styling */
.shoping__cart__table::-webkit-scrollbar {
  height: 8px;
}

.shoping__cart__table::-webkit-scrollbar-thumb {
  background: #7fad39;
  border-radius: 4px;
}

.shoping__cart__table::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .container {
    padding: clamp(0.75rem, 2vw, 1.5rem);
  }

  .shoping__cart__table table {
    min-width: 500px;
  }

  .shoping__cart__table th,
  .shoping__cart__table td {
    padding: clamp(0.4rem, 0.8vw, 0.6rem);
    font-size: clamp(0.7rem, 0.9vw, 0.8rem);
  }

  .shoping__cart__table img {
    width: clamp(50px, 8vw, 60px);
  }

  .quantity {
    flex-wrap: wrap;
    justify-content: center;
  }

  .qty-btn,
  .btn-danger {
    padding: clamp(0.25rem, 0.6vw, 0.4rem) clamp(0.3rem, 0.6vw, 0.5rem);
    font-size: clamp(0.65rem, 0.9vw, 0.75rem);
  }

  .qty-input {
    width: clamp(35px, 7vw, 45px);
    font-size: clamp(0.65rem, 0.9vw, 0.75rem);
  }

  .promo-section input {
    width: 100%;
    margin-bottom: 0.5rem;
  }

  .promo-section button {
    width: 100%;
    text-align: center;
  }
}

@media (max-width: 576px) {
  .container {
    padding: clamp(0.5rem, 1.5vw, 0.75rem);
  }

  .shoping__cart__table table {
    min-width: 400px;
  }

  .shoping__cart__table th,
  .shoping__cart__table td {
    padding: clamp(0.3rem, 0.6vw, 0.4rem);
    font-size: clamp(0.65rem, 0.8vw, 0.7rem);
  }

  .shoping__cart__table img {
    width: clamp(40px, 6vw, 50px);
  }

  .shoping__checkout {
    padding: clamp(0.75rem, 1.5vw, 1rem);
  }

  .primary-btn {
    width: 100%;
    text-align: center;
    font-size: clamp(0.75rem, 1vw, 0.85rem);
  }
}
</style>

<section class="shoping-cart spad">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        <div class="shoping__cart__table">
          <table>
            <thead>
              <tr>
                <th>Products</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$cart_empty): ?>
              <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
              <?php 
                try {
                    $query = "SELECT stock_quantity FROM products WHERE product_id = :product_id";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute(['product_id' => $product_id]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $stock_quantity = $row ? (int)$row['stock_quantity'] : 0;
                } catch (PDOException $e) {
                    $stock_quantity = 0;
                }
              ?>
              <?php if (isset($item['product_name']) && isset($item['price']) && isset($item['quantity']) && isset($item['image_path'])): ?>
              <tr>
                <td>
                  <img src="<?php echo htmlspecialchars($item['image_path'], ENT_QUOTES, 'UTF-8'); ?>"
                    alt="<?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?>">
                  <h5><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                </td>
                <td>
                  Rs <span class="price" data-id="<?php echo htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8'); ?>"
                    data-price="<?php echo $item['price']; ?>"><?php echo number_format($item['price'], 2); ?></span>
                </td>
                <td>
                  <div class="quantity">
                    <button type="button" class="qty-btn dec"
                      data-id="<?php echo htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8'); ?>">-</button>
                    <input type="number" class="qty-input"
                      data-id="<?php echo htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8'); ?>"
                      data-max="<?php echo $stock_quantity; ?>" value="<?php echo $item['quantity']; ?>" min="1">
                    <button type="button" class="qty-btn inc"
                      data-id="<?php echo htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8'); ?>"
                      data-max="<?php echo $stock_quantity; ?>">+</button>
                  </div>
                </td>
                <td>
                  Rs <span class="total-price"
                    data-id="<?php echo htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </td>
                <td>
                  <a href="remove_from_cart.php?remove=<?php echo htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8'); ?>"
                    class="btn btn-danger remove-btn">Remove</a>
                </td>
              </tr>
              <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">Invalid cart item data for product ID
                  <?php echo htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8'); ?>.</td>
              </tr>
              <?php endif; ?>
              <?php endforeach; ?>
              <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">Your cart is empty.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 col-12">
        <div class="shoping__checkout">
          <h5>Cart Total</h5>
          <ul>
            <li>Subtotal <span id="subtotal">Rs <?php echo number_format($subtotal, 2); ?></span></li>
            <?php if (isset($_SESSION['promo_applied']) && $_SESSION['promo_applied']): ?>
            <li>Discount (<?php echo $_SESSION['promo_discount']; ?>%) <span id="discount">- Rs
                <?php echo number_format($discount_amount, 2); ?></span></li>
            <?php endif; ?>
            <li>Total <span id="total">Rs <?php echo number_format($total, 2); ?></span></li>
          </ul>
          <?php if (isset($_SESSION['user_id'])): ?>
          <div class="promo-section">
            <?php if (isset($_SESSION['promo_applied']) && $_SESSION['promo_applied']): ?>
            <p>Promo code <strong><?php echo htmlspecialchars($_SESSION['promo_code'], ENT_QUOTES, 'UTF-8'); ?></strong>
              applied
              (<?php echo $_SESSION['promo_discount']; ?>%)</p>
            <button id="remove_promo">Remove Promo</button>
            <?php endif; ?>

            <?php if ($has_promo_request): ?>
            <p class="promo-message">Your promo code request is pending.</p>
            <?php elseif ($latest_request_status === 'rejected'): ?>
            <p class="promo-message" style="color: red;">Your promo code request was rejected.</p>
            <?php endif; ?>

            <?php if (!$has_promo_request && !$has_available_promo): ?>
            <button id="request_promo" style="margin-top: 5px;">Request Promo Code</button>
            <?php endif; ?>

            <?php if (!$has_promo_request && $has_available_promo && !isset($_SESSION['promo_applied'])): ?>
            <input type="text" id="promo_code" placeholder="Enter promo code">
            <button id="apply_promo">Apply Promo</button>
            <?php endif; ?>
            <div id="promo_message" class="promo-message"></div>
          </div>
          <?php else: ?>
          <p class="promo-section">Please <a href="login.php">login</a> to use promo codes.</p>
          <?php endif; ?>
          <?php if (!$cart_empty): ?>
          <a href="checkout.php" class="primary-btn">PROCEED TO CHECKOUT</a>
          <?php else: ?>
          <a href="javascript:void(0)" class="primary-btn"
            onclick="alert('Your cart is empty. Please add items before checkout.');">PROCEED TO CHECKOUT</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const decButtons = document.querySelectorAll('.dec');
  const incButtons = document.querySelectorAll('.inc');
  const qtyInputs = document.querySelectorAll('.qty-input');
  const applyPromoBtn = document.getElementById('apply_promo');
  const requestPromoBtn = document.getElementById('request_promo');
  const removePromoBtn = document.getElementById('remove_promo');
  const promoMessage = document.getElementById('promo_message');

  decButtons.forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const input = document.querySelector(`.qty-input[data-id='${id}']`);
      let value = parseInt(input.value) || 1;
      if (value > 1) {
        value--;
        input.value = value;
        updateCartItem(id, value);
      }
    });
  });

  incButtons.forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const input = document.querySelector(`.qty-input[data-id='${id}']`);
      const max = parseInt(button.getAttribute('data-max'));
      let value = parseInt(input.value) || 1;

      if (value < max) {
        value++;
        input.value = value;
        updateCartItem(id, value);
      } else {
        alert(
          `You are trying to add more than the available products in stock. Only ${max} items available.`);
      }
    });
  });

  qtyInputs.forEach(input => {
    input.addEventListener('input', function() {
      const id = this.getAttribute('data-id');
      const max = parseInt(this.getAttribute('data-max'));
      let value = parseInt(this.value);

      if (isNaN(value) || value < 1) {
        this.value = 1;
        value = 1;
      } else if (value > max) {
        this.value = max;
        value = max;
        alert(
          `You are trying to add more than the available products in stock. Only ${max} items available.`);
      }

      updateCartItem(id, value);
    });
  });

  if (applyPromoBtn) {
    applyPromoBtn.addEventListener('click', function() {
      const promoCode = document.getElementById('promo_code').value.trim();

      if (!promoCode) {
        promoMessage.innerHTML = '<span style="color: red;">Please enter a promo code</span>';
        return;
      }

      applyPromoBtn.disabled = true;
      promoMessage.innerHTML = '<span style="color: blue;">Applying promo code...</span>';

      fetch('shoping-cart.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `action=apply_promo&promo_code=${encodeURIComponent(promoCode)}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            promoMessage.innerHTML = `<span style="color: red;">${data.error}</span>`;
            applyPromoBtn.disabled = false;
          } else if (data.success) {
            promoMessage.innerHTML = `<span style="color: green;">${data.success}</span>`;
            // Refresh the page to show updated totals and promo status
            location.reload();
          }
        });
    });
  }

  if (requestPromoBtn) {
    requestPromoBtn.addEventListener('click', function() {
      requestPromoBtn.disabled = true;
      promoMessage.innerHTML = '<span style="color: blue;">Processing your request...</span>';
      setTimeout(() => {
        location.reload();
      }, 500);
      fetch('shoping-cart.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'action=request_promo'
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            promoMessage.innerHTML = `<span style="color: green;">${data.success}</span>`;
            // Refresh the page to show updated request status
            location.reload();
          } else if (data.error) {
            promoMessage.innerHTML = `<span style="color: red;">${data.error}</span>`;
            requestPromoBtn.disabled = false;
          }
        });
    });
  }

  if (removePromoBtn) {
    removePromoBtn.addEventListener('click', function() {
      removePromoBtn.disabled = true;
      promoMessage.innerHTML = '<span style="color: blue;">Removing promo code...</span>';
      setTimeout(() => {
        location.reload();
      }, 500);

      fetch('shoping-cart.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'action=remove_promo'
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            promoMessage.innerHTML = `<span style="color: green;">${data.success}</span>`;
            // Refresh the page to show updated totals and promo status
            location.reload();
          } else if (data.error) {
            promoMessage.innerHTML = `<span style="color: red;">${data.error}</span>`;
            removePromoBtn.disabled = false;
          }
        });
    });
  }

  function updateCartItem(id, quantity) {
    const price = parseFloat(document.querySelector(`.price[data-id='${id}']`).getAttribute('data-price'));
    const totalPrice = (price * quantity).toFixed(2);
    document.querySelector(`.total-price[data-id='${id}']`).textContent = totalPrice;

    updateTotals();

    fetch('shoping-cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update_cart&id=${encodeURIComponent(id)}&quantity=${encodeURIComponent(quantity)}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          alert(data.error);
          const input = document.querySelector(`.qty-input[data-id='${id}']`);
          input.value = Math.min(quantity, parseInt(input.getAttribute('data-max')));
          updateCartItem(id, parseInt(input.value));
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  function updateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.total-price').forEach(item => {
      const value = parseFloat(item.textContent);
      if (!isNaN(value)) {
        subtotal += value;
      }
    });

    const discountApplied =
      <?php echo isset($_SESSION['promo_applied']) && $_SESSION['promo_applied'] ? 'true' : 'false'; ?>;
    const discountPercent = <?php echo isset($_SESSION['promo_discount']) ? $_SESSION['promo_discount'] : 0; ?>;

    document.getElementById('subtotal').textContent = `Rs ${subtotal.toFixed(2)}`;

    if (discountApplied) {
      const discountAmount = subtotal * (discountPercent / 100);
      document.getElementById('discount').textContent = `- Rs ${discountAmount.toFixed(2)}`;
      document.getElementById('total').textContent = `Rs ${(subtotal - discountAmount).toFixed(2)}`;
    } else {
      document.getElementById('total').textContent = `Rs ${subtotal.toFixed(2)}`;
    }
  }
});
</script>

<?php include("components/footer.php"); ?>