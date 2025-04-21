<?php
include("components/header.php");

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
            echo json_encode(['stock_quantity' => 0]); // Product not found
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
        $query = "SELECT stock_quantity FROM products WHERE product_id = :product_id";
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

        echo json_encode(['success' => 'Cart updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error']);
    }
    exit();
}

// Clear cart if requested
if (isset($_GET['ch'])) {
    unset($_SESSION['cart']);
}

// Calculate subtotal and total
$subtotal = 0;
$cart_empty = true;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_empty = false;
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['price']) && isset($item['quantity'])) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }
}
$total = $subtotal;
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
  background: rgb(10, 162, 51);
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

.qty-input {
  width: 50px;
  text-align: center;
  border: 1px solid #ccc;
  font-size: 16px;
  margin: 0 5px;
  padding: 3px;
  border-radius: 3px;
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
                // Fetch stock quantity for each product
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
            <li>Total <span id="total">Rs <?php echo number_format($total, 2); ?></span></li>
          </ul>
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

  function updateCartItem(id, quantity) {
    // Update total price for the item
    const price = parseFloat(document.querySelector(`.price[data-id='${id}']`).getAttribute('data-price'));
    const totalPrice = (price * quantity).toFixed(2);
    document.querySelector(`.total-price[data-id='${id}']`).textContent = totalPrice;

    // Update totals in UI
    updateTotals();

    // Send AJAX request to update cart
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
          // Revert quantity if stock is exceeded
          const input = document.querySelector(`.qty-input[data-id='${id}']`);
          input.value = Math.min(quantity, parseInt(input.getAttribute('data-max')));
          updateCartItem(id, parseInt(input.value)); // Recalculate with valid quantity
        } else if (data.success) {
          // Optionally show a success message
          // alert(data.success);
        }
      })
      .catch(error => {

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

    // Update DOM with Rs prefix
    document.getElementById('subtotal').textContent = `Rs ${subtotal.toFixed(2)}`;
    document.getElementById('total').textContent = `Rs ${subtotal.toFixed(2)}`;
  }
});
</script>

<?php include("components/footer.php"); ?>