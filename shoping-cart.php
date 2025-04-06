<style>
.shoping__cart__table img {
  width: 80px;
  /* Adjust image size */
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
<?php
include("components/header.php");

// Handle updating the cart quantities
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = max(1, (int)$quantity);
        }
    }
    $_SESSION['message'] = "Cart updated successfully!";
    header("Location: shoping-cart.php");
    exit();
}

// Calculate the subtotal and total
$subtotal = 0;
$cart_empty = true; // Initialize as empty

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_empty = false; // Cart has items
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['price']) && isset($item['quantity'])) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }
}
$total = $subtotal; // Assuming no tax or shipping for now
?>

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
          <form action="shoping-cart.php" method="POST">
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
                <?php if (isset($item['product_name']) && isset($item['price']) && isset($item['quantity'])): ?>
                <tr>
                  <td>
                    <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['product_name']; ?>">
                    <h5><?php echo $item['product_name']; ?></h5>
                  </td>
                  <td>
                    $<span class="price" data-id="<?php echo $product_id; ?>"
                      data-price="<?php echo $item['price']; ?>"><?php echo $item['price']; ?></span>
                  </td>
                  <td>
                    <div class="quantity">
                      <button type="button" class="qty-btn dec" data-id="<?php echo $product_id; ?>">-</button>
                      <input type="text" name="quantity[<?php echo $product_id; ?>]" class="qty-input"
                        data-id="<?php echo $product_id; ?>" value="<?php echo $item['quantity']; ?>">
                      <button type="button" class="qty-btn inc" data-id="<?php echo $product_id; ?>">+</button>
                    </div>
                  </td>
                  <td>
                    <span class="total-price"
                      data-id="<?php echo $product_id; ?>"><?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                  </td>
                  <td>
                    <a href="remove_from_cart.php?remove=<?php echo $product_id; ?>"
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
            <div class="row">

            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <div class="shoping__checkout">
          <h5>Cart Total</h5>
          <ul>
            <li>Subtotal <span id="subtotal">$<?php echo number_format($subtotal, 2); ?></span></li>
            <li>Total <span id="total">$<?php echo number_format($total, 2); ?></span></li>
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

<?php include("components/footer.php"); ?>