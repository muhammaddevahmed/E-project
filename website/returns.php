<style>
/* General Styling */
.returns {
  padding: 60px 0;
  background-color: #f9f9f9;
}

.returns__form__title h2 {
  font-size: 28px;
  font-weight: 700;
  color: #333;
  text-align: center;
  margin-bottom: 30px;
}

.returns__input {
  margin-bottom: 20px;
}

.returns__input label {
  display: block;
  font-size: 16px;
  font-weight: 600;
  color: #555;
  margin-bottom: 8px;
}

.returns__input label span {
  color: #ff4c4c;
}

.returns__input select,
.returns__input textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 14px;
  color: #333;
  background-color: #fff;
  transition: border-color 0.3s ease;
}

.returns__input select:focus,
.returns__input textarea:focus {
  border-color: #28a745;
  outline: none;
}

.returns__input textarea {
  height: 120px;
  resize: vertical;
}

.site-btn {
  display: inline-block;
  padding: 12px 30px;
  font-size: 16px;
  font-weight: 600;
  color: #fff;
  background-color: #28a745;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.site-btn:hover {
  background-color: #218838;
}

/* Responsive Design */
@media (max-width: 768px) {
  .returns__form__title h2 {
    font-size: 24px;
  }

  .returns__input select,
  .returns__input textarea {
    font-size: 14px;
  }

  .site-btn {
    width: 100%;
    padding: 12px;
  }
}

.returns-status {
  padding: 60px 0;
  background-color: #fff;
}

.section-title h2 {
  font-size: 28px;
  font-weight: 700;
  color: #333;
  text-align: center;
  margin-bottom: 30px;
}

.table {
  width: 100%;
  margin-bottom: 1rem;
  color: #212529;
  border-collapse: collapse;
}

.table th {
  background-color: #f8f9fa;
  font-weight: 600;
}

.table th,
.table td {
  padding: 12px;
  vertical-align: top;
  border: 1px solid #dee2e6;
}

.text-success {
  color: #28a745 !important;
}

.text-danger {
  color: #dc3545 !important;
}

.text-warning {
  color: #ffc107 !important;
}

.font-weight-bold {
  font-weight: 700 !important;
}

.alert-info {
  color: #0c5460;
  background-color: #d1ecf1;
  border-color: #bee5eb;
  padding: 15px;
  border-radius: 4px;
}
</style>

<?php
include("components/header.php");

// Start session to get logged-in user ID

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to request a return.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id']; // Get logged-in user ID

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderId = htmlspecialchars($_POST['order_id']);
    $productId = htmlspecialchars($_POST['product_id']);
    $reason = htmlspecialchars($_POST['reason']);

    // Validate order_id and product_id for the logged-in user
    $orderExists = false;
    $productExists = false;

    // Check if the order belongs to the logged-in user
    $orderQuery = "SELECT order_id FROM orders WHERE order_id = :order_id AND u_id = :user_id";
    $orderStmt = $pdo->prepare($orderQuery);
    $orderStmt->bindParam(':order_id', $orderId);
    $orderStmt->bindParam(':user_id', $user_id);
    $orderStmt->execute();
    if ($orderStmt->rowCount() > 0) {
        $orderExists = true;
    }

    // Check if product_id exists in the order
    $productQuery = "SELECT product_id FROM orders WHERE product_id = :product_id AND order_id = :order_id";
    $productStmt = $pdo->prepare($productQuery);
    $productStmt->bindParam(':product_id', $productId);
    $productStmt->bindParam(':order_id', $orderId);
    $productStmt->execute();
    if ($productStmt->rowCount() > 0) {
        $productExists = true;
    }

    // Insert the return request if valid
    if ($orderExists && $productExists) {
        $sql = "INSERT INTO returns (order_id, product_id, reason, return_status, return_date)
                VALUES (:order_id, :product_id, :reason, 'pending', NOW())";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':order_id', $orderId);
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':reason', $reason);
            $stmt->execute();

            echo "<script>alert('Your return request has been submitted successfully.'); window.location.href='index.php';</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Invalid Order ID or Product ID.');</script>";
    }
}
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg"
  data-setbg="https://i.pinimg.com/736x/72/e6/21/72e62198095a1c36038869ddf05481f7.jpg">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <div class="breadcrumb__text">
          <h2>Returns</h2>
          <div class="breadcrumb__option">
            <a href="./index.php">Home</a>
            <span>Returns</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Breadcrumb Section End -->



<!-- Returns Section Begin -->
<section class="returns spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="returns__form__title">
          <h2>Request a Return</h2>
        </div>
      </div>
    </div>
    <form action="" method="POST">
      <div class="row">
        <div class="col-lg-6 col-md-6">
          <div class="returns__input">
            <label for="order_id">Order ID<span>*</span></label>
            <select name="order_id" id="order_id" required>
              <option value="">Select Order ID</option>
              <?php
                            // Fetch order IDs belonging to the logged-in user
                            $orderQuery = "SELECT order_id FROM orders WHERE u_id = :user_id";
                            $orderStmt = $pdo->prepare($orderQuery);
                            $orderStmt->bindParam(':user_id', $user_id);
                            $orderStmt->execute();
                            while ($order = $orderStmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$order['order_id']}'>{$order['order_id']}</option>";
                            }
                            ?>
            </select>
          </div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="returns__input">
            <label for="product_id">Product ID<span>*</span></label>
            <select name="product_id" id="product_id" required>
              <option value="">Select Product ID</option>
              <?php
                            // Fetch product IDs from the user's orders
                            $productQuery = "SELECT DISTINCT product_id FROM orders WHERE u_id = :user_id";
                            $productStmt = $pdo->prepare($productQuery);
                            $productStmt->bindParam(':user_id', $user_id);
                            $productStmt->execute();
                            while ($product = $productStmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$product['product_id']}'>{$product['product_id']}</option>";
                            }
                            ?>
            </select>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="returns__input">
            <label for="reason">Reason for Return<span>*</span></label>
            <textarea name="reason" id="reason" placeholder="Please explain the reason for return" required></textarea>
          </div>
        </div>
        <div class="col-lg-12 text-center">
          <button type="submit" class="site-btn">SUBMIT RETURN REQUEST</button>
        </div>
      </div>
    </form>
  </div>
</section>
<!-- Returns Section End -->

<!-- Add this section after your form to display return status -->
<section class="returns-status spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="section-title">
          <h2>Your Return Status</h2>
        </div>

        <?php
        // Fetch all return requests for this user
        $returnsQuery = "SELECT r.*, p.product_name, o.date_time 
                        FROM returns r
                        JOIN products p ON r.product_id = p.product_id
                        JOIN orders o ON r.order_id = o.order_id
                        WHERE o.u_id = :user_id
                        ORDER BY r.return_date DESC";
        $returnsStmt = $pdo->prepare($returnsQuery);
        $returnsStmt->bindParam(':user_id', $user_id);
        $returnsStmt->execute();
        
        if ($returnsStmt->rowCount() > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Return ID</th>
                <th>Order ID</th>
                <th>Product</th>
                <th>Date Requested</th>
                <th>Status</th>
                <th>Reason</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($return = $returnsStmt->fetch(PDO::FETCH_ASSOC)): 
                  // Determine status color
                  $statusClass = '';
                  switch ($return['return_status']) {
                    case 'approved':
                      $statusClass = 'text-success';
                      $statusText = 'Approved';
                      break;
                    case 'rejected':
                      $statusClass = 'text-danger';
                      $statusText = 'Rejected';
                      break;
                    default:
                      $statusClass = 'text-warning';
                      $statusText = 'Pending';
                  }
                ?>
              <tr>
                <td><?= htmlspecialchars($return['return_id']) ?></td>
                <td><?= htmlspecialchars(substr($return['order_id'], -6)) ?></td>
                <td><?= htmlspecialchars($return['product_name']) ?></td>
                <td><?= date('M j, Y', strtotime($return['return_date'])) ?></td>
                <td class="<?= $statusClass ?> font-weight-bold"><?= $statusText ?></td>
                <td><?= nl2br(htmlspecialchars($return['reason'])) ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="alert alert-info">You haven't submitted any return requests yet.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>



<?php
include("components/footer.php");
?>