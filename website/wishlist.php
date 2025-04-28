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


<script src="https://cdn.tailwindcss.com"></script>


<body class="bg-gray-100 font-sans min-h-screen">
  <section class="py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
          <i class="fas fa-heart text-red-500 mr-3"></i>My Wishlist
        </h2>
        <p class="text-gray-600 mt-4 text-base sm:text-lg max-w-2xl mx-auto">
          Your favorite items all in one place. Save products you love to purchase them later.
        </p>
        <div class="mt-3 h-1 w-20 bg-green-500 mx-auto rounded"></div>
      </div>

      <?php if (count($wishlist_items) > 0): ?>
      <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 animate-fade-in">
        <div class="grid grid-cols-1 gap-6 sm:gap-8">
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
          <div
            class="bg-gray-50 rounded-xl p-6 flex flex-col sm:flex-row items-start sm:items-center gap-6 border border-gray-200 hover:-translate-y-1 transition-transform duration-300 wishlist-item"
            id="wishlist-item-<?php echo $item['product_id']; ?>">
            <div class="flex-shrink-0">
              <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-lg border border-gray-200 hover:scale-105 transition-transform duration-200">
            </div>
            <div class="flex-1">
              <a href="product-details.php?id=<?php echo $item['product_id']; ?>"
                class="text-lg font-semibold text-gray-900 hover:text-green-500 transition-colors duration-200">
                <?php echo htmlspecialchars($item['product_name']); ?>
              </a>
              <p class="text-green-500 font-bold text-lg mt-1">Rs <?php echo number_format($item['price'], 2); ?></p>
              <p class="text-sm text-gray-600 mt-1">Added on:
                <?php echo date('M j, Y', strtotime($item['added_at'])); ?></p>
              <p
                class="text-sm font-medium mt-1 <?php echo $item['stock_quantity'] > 0 ? 'text-green-600' : 'text-red-600'; ?>">
                <?php echo $item['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
              </p>
            </div>
            <div class="flex flex-col items-center sm:items-end gap-4 w-full sm:w-auto">
              <?php if ($item['stock_quantity'] > 0): ?>
              <form action="add_to_cart.php" method="POST" class="flex items-center gap-2"
                onsubmit="return validateQuantity(this)">
                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['product_name']); ?>">
                <input type="hidden" name="price" value="<?php echo $item['price']; ?>">
                <input type="hidden" name="image_path" value="<?php echo $image_url; ?>">
                <div class="flex items-center bg-gray-200 rounded-lg">
                  <button type="button"
                    class="qty-btn1 dec bg-red-500 text-white px-3 py-1 rounded-l-lg hover:bg-red-600 transition-colors">-</button>
                  <input type="text" name="quantity"
                    class="qty-input w-12 text-center bg-white border-y border-gray-200 text-sm py-1" value="1" min="1"
                    max="<?php echo $item['stock_quantity']; ?>" data-max="<?php echo $item['stock_quantity']; ?>">
                  <button type="button"
                    class="qty-btn inc bg-green-500 text-white px-3 py-1 rounded-r-lg hover:bg-green-600 transition-colors">+</button>
                </div>
                <button type="submit"
                  class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-600 hover:scale-105 active:scale-95 transition-all">
                  Add to Cart
                </button>
              </form>
              <?php else: ?>
              <button class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold cursor-not-allowed"
                disabled>
                Out of Stock
              </button>
              <?php endif; ?>
              <button onclick="removeFromWishlist(<?php echo $item['product_id']; ?>)"
                class="text-red-500 hover:bg-red-100 rounded-full p-2 transition-all hover:rotate-90"
                aria-label="Remove from wishlist">
                <i class="fas fa-trash text-lg"></i>
              </button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="mt-8 flex flex-col sm:flex-row justify-between gap-4">
          <a href="shop-grid.php"
            class="bg-white border-2 border-green-500 text-green-500 px-6 py-3 rounded-lg font-semibold text-center hover:bg-green-500 hover:text-white transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
          </a>
          <a href="shoping-cart.php"
            class="bg-green-500 text-white px-6 py-3 rounded-lg font-semibold text-center hover:bg-green-600 hover:scale-105 transition-all">
            <i class="fas fa-shopping-cart mr-2"></i>View Cart
          </a>
        </div>
      </div>
      <?php else: ?>
      <div class="bg-white rounded-2xl shadow-xl p-8 text-center animate-fade-in">
        <i class="fas fa-heart text-gray-300 text-6xl sm:text-7xl mb-6"></i>
        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Your Wishlist is Empty</h3>
        <p class="text-gray-600 mb-8 text-base sm:text-lg max-w-md mx-auto">
          You haven't added any items to your wishlist yet. Start browsing our collection and add your favorite
          products.
        </p>
        <a href="shop-grid.php"
          class="inline-block bg-green-500 text-white px-6 py-3 rounded-lg font-semibold text-base sm:text-lg hover:bg-green-600 hover:-translate-y-1 transition-all">
          <i class="fas fa-shopping-bag mr-2"></i>Start Shopping
        </a>
      </div>
      <?php endif; ?>
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

            // If no items left, show empty state and reload the page
            if (document.querySelectorAll('.wishlist-item').length === 0) {
              document.querySelector('.bg-white.rounded-2xl').innerHTML = `
                <div class="text-center p-8">
                  <i class="fas fa-heart text-gray-300 text-6xl sm:text-7xl mb-6"></i>
                  <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Your Wishlist is Empty</h3>
                  <p class="text-gray-600 mb-8 text-base sm:text-lg max-w-md mx-auto">
                    You haven't added any items to your wishlist yet. Start browsing our collection and add your favorite products.
                  </p>
                  <a href="shop-grid.php" 
                     class="inline-block bg-green-500 text-white px-6 py-3 rounded-lg font-semibold text-base sm:text-lg hover:bg-green-600 hover:-translate-y-1 transition-all">
                    <i class="fas fa-shopping-bag mr-2"></i>Start Shopping
                  </a>
                </div>
              `;
              setTimeout(() => {
                location.reload();
              }, 500);
            } else {
              showToast('Product removed from wishlist');
            }
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
    toast.className =
      'fixed bottom-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 text-sm font-semibold animate-fade-in';
    toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    document.body.appendChild(toast);

    setTimeout(() => {
      toast.className += ' opacity-0 transition-opacity duration-500';
      setTimeout(() => toast.remove(), 500);
    }, 3000);
  }
  </script>

  <?php include("components/footer.php"); ?>