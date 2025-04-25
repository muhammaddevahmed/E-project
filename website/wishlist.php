<?php
include("components/header.php");

if (!isset($_SESSION['user_id'])) {
  echo "<script>alert('You must be logged in to see your wishlist.'); window.location.href='login.php';</script>";
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch wishlist items with product details
$stmt = $pdo->prepare("SELECT p.*, w.added_at 
                       FROM wishlist w 
                       JOIN products p ON w.product_id = p.product_id 
                       WHERE w.user_id = ? 
                       ORDER BY w.added_at DESC");
$stmt->execute([$user_id]);
$wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Wishlist - Gift & Stationery Shop</title>
  <style>
  .wishlist-page {
    padding: 60px 0;
    background: #f9f9f9;
  }

  .wishlist-header {
    text-align: center;
    margin-bottom: 50px;
  }

  .wishlist-header h2 {
    font-size: 36px;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
    position: relative;
    display: inline-block;
  }

  .wishlist-header h2:after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: #7fad39;
  }

  .wishlist-header p {
    color: #777;
    font-size: 18px;
    max-width: 700px;
    margin: 0 auto;
  }

  .wishlist-container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 30px rgba(0, 0, 0, 0.05);
    padding: 30px;
    margin-bottom: 40px;
  }

  .wishlist-table {
    width: 100%;
    border-collapse: collapse;
  }

  .wishlist-table thead th {
    padding: 15px;
    text-align: left;
    border-bottom: 2px solid #f3f3f3;
    font-weight: 600;
    color: #333;
    text-transform: uppercase;
    font-size: 14px;
    letter-spacing: 1px;
  }

  .wishlist-item {
    border-bottom: 1px solid #f3f3f3;
    transition: all 0.3s;
  }

  .wishlist-item:hover {
    background: rgba(127, 173, 57, 0.03);
  }

  .wishlist-item td {
    padding: 25px 15px;
    vertical-align: middle;
  }

  .product-thumbnail {
    width: 120px;
  }

  .product-thumbnail img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #f1f1f1;
    transition: transform 0.3s;
  }

  .product-thumbnail img:hover {
    transform: scale(1.05);
  }

  .product-name a {
    color: #333;
    font-weight: 600;
    font-size: 16px;
    transition: color 0.3s;
  }

  .product-name a:hover {
    color: #7fad39;
    text-decoration: none;
  }

  .product-price {
    font-weight: 700;
    color: #7fad39;
    font-size: 18px;
  }

  .product-stock {
    font-size: 14px;
    color: #28a745;
    font-weight: 500;
  }

  .product-stock.out {
    color: #dc3545;
  }

  .product-add-cart .btn {
    padding: 10px 25px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 30px;
    background: #7fad39;
    color: white;
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .product-add-cart .btn:hover {
    background: #6a9a2b;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(127, 173, 57, 0.3);
  }

  .product-remove button {
    background: none;
    border: none;
    color: #ff4757;
    font-size: 22px;
    cursor: pointer;
    transition: all 0.3s;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .product-remove button:hover {
    background: rgba(255, 71, 87, 0.1);
    transform: rotate(90deg);
  }

  .empty-wishlist {
    text-align: center;
    padding: 80px 0;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 30px rgba(0, 0, 0, 0.05);
  }

  .empty-wishlist i {
    font-size: 80px;
    color: #e0e0e0;
    margin-bottom: 25px;
  }

  .empty-wishlist h3 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #333;
    font-weight: 700;
  }

  .empty-wishlist p {
    color: #777;
    font-size: 16px;
    margin-bottom: 30px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
  }

  .empty-wishlist .btn {
    padding: 12px 35px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 30px;
    background: #7fad39;
    color: white;
    transition: all 0.3s;
  }

  .empty-wishlist .btn:hover {
    background: #6a9a2b;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(127, 173, 57, 0.3);
  }

  .wishlist-actions {
    margin-top: 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .wishlist-actions .btn {
    padding: 12px 35px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 30px;
    transition: all 0.3s;
  }

  .wishlist-actions .btn-outline {
    background: transparent;
    border: 2px solid #7fad39;
    color: #7fad39;
  }

  .wishlist-actions .btn-outline:hover {
    background: #7fad39;
    color: white;
  }

  /* Quantity control styles */
  .quantity-control {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
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

  .qty-btn1 {
    background: rgb(208, 35, 35);
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 3px;
    font-size: 16px;
  }

  .qty-btn:hover {
    background: rgb(119, 195, 154);
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

  .btnNew {
    background-color: #7fad39;
  }

  @media (max-width: 768px) {
    .wishlist-table thead {
      display: none;
    }

    .wishlist-item {
      display: block;
      margin-bottom: 30px;
      border: 1px solid #f3f3f3;
      border-radius: 10px;
      padding: 20px;
    }

    .wishlist-item td {
      display: flex;
      justify-content: space-between;
      padding: 15px 0;
      border: none;
      position: relative;
      padding-left: 50%;
    }

    .wishlist-item td:before {
      content: attr(data-label);
      font-weight: 600;
      color: #333;
      position: absolute;
      left: 15px;
      width: 45%;
      padding-right: 10px;
    }

    .product-thumbnail {
      margin-bottom: 20px;
      width: 100%;
    }

    .product-thumbnail img {
      width: 100%;
      height: auto;
      max-height: 200px;
    }

    .wishlist-actions {
      flex-direction: column;
      gap: 15px;
    }

    .wishlist-actions .btn {
      width: 100%;
    }

    /* Mobile styles for quantity controls */
    .quantity-control {
      flex-direction: column;
      align-items: flex-start;
    }

    .quantity-control .primary-btn {
      margin-bottom: 10px;
    }

    .qty-btn,
    .qty-btn1,
    .qty-input {
      margin: 5px 0;
    }
  }
  </style>
</head>

<body>
  <section class="wishlist-page">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="wishlist-header">
            <h2><i class="fa fa-heart" style="color: #ff4757; margin-right: 10px;"></i> My Wishlist</h2>
            <p>Your favorite items all in one place. Save products you love to purchase them later.</p>
          </div>

          <?php if (count($wishlist_items) > 0): ?>
          <div class="wishlist-container">
            <table class="wishlist-table">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Name</th>
                  <th>Price</th>
                  <th>Added On</th>
                  <th>Action</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($wishlist_items as $item): 
                  // Image path logic
                  $web_root = 'http://localhost/EProject/';
                  $actual_storage = 'edashboard/html/images/products/';
                  $default_image = $web_root . 'edashboard/html/images/default-product.jpg';
                  
                  $filename = basename($item['image_path']);
                  $relative_path = $actual_storage . $filename;
                  $absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/EProject/' . $relative_path;
                  $image_url = $web_root . $relative_path;
                  
                  if (empty($item['image_path']) || !file_exists($absolute_path)) {
                      $image_url = $default_image;
                  }
                ?>
                <tr class="wishlist-item" id="wishlist-item-<?php echo $item['product_id']; ?>">
                  <td class="product-thumbnail" data-label="Product">
                    <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                  </td>
                  <td class="product-name" data-label="Name">
                    <a href="product-details.php?id=<?php echo $item['product_id']; ?>">
                      <?php echo htmlspecialchars($item['product_name']); ?>
                    </a>
                  </td>
                  <td class="product-price" data-label="Price">
                    Rs <?php echo number_format($item['price'], 2); ?>
                  </td>
                  <td data-label="Added On">
                    <?php echo date('M j, Y', strtotime($item['added_at'])); ?>
                  </td>
                  <td class="product-add-cart" data-label="Action">
                    <?php if ($item['stock_quantity'] > 0): ?>
                    <form action="add_to_cart.php" method="POST" style="display: inline;"
                      onsubmit="return validateQuantity(this)">
                      <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                      <input type="hidden" name="product_name"
                        value="<?php echo htmlspecialchars($item['product_name']); ?>">
                      <input type="hidden" name="price" value="<?php echo $item['price']; ?>">
                      <input type="hidden" name="image_path" value="<?php echo $image_url; ?>">
                      <button type="submit" class="primary-btn">ADD TO CART</button>
                      <button type="button" class="qty-btn1 dec">-</button>
                      <input type="text" name="quantity" class="qty-input" value="1" min="1"
                        max="<?php echo $item['stock_quantity']; ?>" data-max="<?php echo $item['stock_quantity']; ?>">
                      <button type="button" class="qty-btn inc">+</button>
                    </form>
                    <?php else: ?>
                    <button class="primary-btn" disabled
                      style="background-color:rgb(228, 49, 49); cursor: not-allowed;">
                      OUT OF STOCK
                    </button>
                    <?php endif; ?>
                  </td>
                  <td class="product-remove" data-label="Remove">
                    <button onclick="removeFromWishlist(<?php echo $item['product_id']; ?>)">
                      <i class="fa fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <div class="wishlist-actions">
              <a href="shop-grid.php" class="btn btn-outline">
                <i class="fa fa-arrow-left mr-2"></i> Continue Shopping
              </a>
              <a href="shoping-cart.php" class="btn">
                <i class="fa fa-shopping-cart mr-2"></i> View Cart
              </a>
            </div>
          </div>
          <?php else: ?>
          <div class="empty-wishlist">
            <i class="fa fa-heart"></i>
            <h3>Your Wishlist is Empty</h3>
            <p>You haven't added any items to your wishlist yet. Start browsing our collection and add your favorite
              products.</p>
            <a href="shop-grid.php" class="btn">
              <i class="fa fa-shopping-bag mr-2"></i> Start Shopping
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <script>
  // Quantity increment/decrement functionality
  document.addEventListener('DOMContentLoaded', function() {
    const decButtons = document.querySelectorAll('.dec');
    const incButtons = document.querySelectorAll('.inc');
    const qtyInputs = document.querySelectorAll('.qty-input');

    decButtons.forEach(button => {
      button.addEventListener('click', function() {
        const input = this.nextElementSibling;
        let value = parseInt(input.value);
        if (value > 1) {
          input.value = value - 1;
        }
      });
    });

    incButtons.forEach(button => {
      button.addEventListener('click', function() {
        const input = this.previousElementSibling;
        let value = parseInt(input.value);
        const max = parseInt(input.getAttribute('data-max'));
        if (value < max) {
          input.value = value + 1;
        } else {
          alert('You are trying to add more than the available products in stock.');
        }
      });
    });

    // Prevent manual input exceeding stock
    qtyInputs.forEach(input => {
      input.addEventListener('input', function() {
        const max = parseInt(this.getAttribute('data-max'));
        let value = parseInt(this.value);
        if (isNaN(value) || value < 1) {
          this.value = 1;
        } else if (value > max) {
          this.value = max;
          alert('You are trying to add more than the available products in stock.');
        }
      });
    });
  });

  // Validate quantity on form submission
  function validateQuantity(form) {
    const qtyInput = form.querySelector('.qty-input');
    const max = parseInt(qtyInput.getAttribute('data-max'));
    const value = parseInt(qtyInput.value);

    if (value > max) {
      alert('You are trying to add more than the available products in stock.');
      return false;
    }
    return true;
  }

  function removeFromWishlist(productId) {
    if (!confirm('Are you sure you want to remove this item from your wishlist?')) {
      return;
    }

    fetch('wishlist_action.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=remove&product_id=${productId}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Remove the item from the DOM with animation
          const item = document.getElementById(`wishlist-item-${productId}`);
          item.style.transition = 'all 0.3s';
          item.style.opacity = '0';
          item.style.transform = 'translateX(100px)';

          setTimeout(() => {
            item.remove();

            // If no items left, show empty state
            if (document.querySelectorAll('.wishlist-item').length === 0) {
              document.querySelector('.wishlist-container').innerHTML = `
                                <div class="empty-wishlist">
                                    <i class="fa fa-heart"></i>
                                    <h3>Your Wishlist is Empty</h3>
                                    <p>You haven't added any items to your wishlist yet. Start browsing our collection and add your favorite products.</p>
                                    <a href="shop-grid.php" class="btn">
                                        <i class="fa fa-shopping-bag mr-2"></i> Start Shopping
                                    </a>
                                </div>
                            `;
            }

            showToast('Product removed from wishlist');
          }, 300);
        } else {
          alert(data.message || 'An error occurred');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
      });
  }

  function showToast(message) {
    const toast = document.createElement('div');
    toast.style.position = 'fixed';
    toast.style.bottom = '20px';
    toast.style.right = '20px';
    toast.style.backgroundColor = '#7fad39';
    toast.style.color = 'white';
    toast.style.padding = '12px 24px';
    toast.style.borderRadius = '5px';
    toast.style.zIndex = '1000';
    toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    toast.style.fontWeight = '500';
    toast.style.display = 'flex';
    toast.style.alignItems = 'center';
    toast.style.gap = '10px';
    toast.innerHTML = `<i class="fa fa-check-circle"></i> ${message}`;

    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.transition = 'opacity 0.5s';
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 500);
    }, 3000);
  }
  </script>

  <?php include("components/footer.php"); ?>
</body>

</html>