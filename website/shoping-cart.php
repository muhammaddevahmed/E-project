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
            $_SESSION['cart'][$item['product_id']] = [
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'product_name' => $item['product_name'],
                'image_path' => $item['image_path']
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
        $_SESSION['cart'][$id]['quantity'] = $quantity;
        $_SESSION['cart'][$id]['price'] = $row['price'];
        $_SESSION['cart'][$id]['product_name'] = $row['product_name'];
        $_SESSION['cart'][$id]['image_path'] = $row['image_path'];

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
                'image_path' => $row['image_path']
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
.shoping__cart__table img {
  width: 80px;
  height: auto;
  border-radius: 5px;
}

.quantity {
  display: flex;
  align-items: center;
}

.qty-btn {
  background: rgb(10, 162, 51 51);
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  border-radius: 3px;
  font-size: 16px;
}

.dec {
  background: rgb(208, 35, 35);
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  border-radius: 3px;
  font-size: 16px;
}

.inc {
  background: #7fad39;
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  border-radius: 3px;
  font-size: 16px;
}

.qty-input {
  width: 50px;
  text-align: center;
  border: 1px solid #ccc;
  font-size: 16px;
  margin: 0 5px;
  padding: 3px;
  border-radius: 3px;
}

.promo-section {
  margin-top: 15px;
}

.promo-section input {
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 3px;
  width: 150px;
  margin-right: 5px;
}

.promo-section button {
  padding: 5px 10px;
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

.promo-message {
  margin-top: 5px;
  font-size: 14px;
}

#remove_promo {
  background: rgb(208, 35, 35);
}

.primary-btn {
  margin-top: 16px
}
</style>

<section class="shoping-cart spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
          <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
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
              <?php if (isset($item['product_name']) && isset($item['price']) && isset($item['quantity'])): ?>
              <tr>
                <td>
                  <img src="<?php echo htmlspecialchars($item['image_path']); ?>"
                    alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                  <h5><?php echo htmlspecialchars($item['product_name']); ?></h5>
                </td>
                <td>
                  Rs <span class="price" data-id="<?php echo htmlspecialchars($product_id); ?>"
                    data-price="<?php echo $item['price']; ?>"><?php echo number_format($item['price'], 2); ?></span>
                </td>
                <td>
                  <div class="quantity">
                    <button type="button" class="qty-btn dec"
                      data-id="<?php echo htmlspecialchars($product_id); ?>">-</button>
                    <input type="number" class="qty-input" data-id="<?php echo htmlspecialchars($product_id); ?>"
                      data-max="<?php echo $stock_quantity; ?>" value="<?php echo $item['quantity']; ?>" min="1">
                    <button type="button" class="qty-btn inc" data-id="<?php echo htmlspecialchars($product_id); ?>"
                      data-max="<?php echo $stock_quantity; ?>">+</button>
                  </div>
                </td>
                <td>
                  Rs <span class="total-price"
                    data-id="<?php echo htmlspecialchars($product_id); ?>"><?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </td>
                <td>
                  <a href="remove_from_cart.php?remove=<?php echo htmlspecialchars($product_id); ?>"
                    class="btn btn-danger remove-btn">Remove</a>
                </td>
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
      <div class="col-lg-6">
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
            <p>Promo code <strong><?php echo htmlspecialchars($_SESSION['promo_code']); ?></strong> applied
              (<?php echo $_SESSION['promo_discount']; ?>%)</p>

            <?php else: ?>

            <?php if ($has_promo_request): ?>
            <p class="promo-message">Your promo code request is pending.</p>
            <?php elseif ($latest_request_status === 'approved'): ?>
            <p class="promo-message" style="color: green;">Your promo code request was accepted.</p>
            <?php elseif ($latest_request_status === 'rejected'): ?>
            <p class="promo-message" style="color: red;">Your promo code request was rejected.</p>
            <?php elseif (!$has_available_promo && !$latest_request_status): ?>
            <button id="request_promo" style="margin-top: 5px;">Request Promo Code</button>
            <?php endif; ?>
            <div id="promo_message" class="promo-message"></div>
            <?php endif; ?>
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
      }, 1000);

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
            // Refresh the page to show updated request status
            location.reload();
          } else if (data.error) {
            promoMessage.innerHTML = `<span style="color: red;">${data.error}</span>`;
            requestPromoBtn.disabled = false;
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