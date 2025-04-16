<?php
include("components/header.php");

// Handle order status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['order_id'])) {
        try {
            $order_id = $_POST['order_id'];
            $new_status = '';
            
            if ($_POST['action'] === 'accept') {
                $new_status = 'accepted';
            } elseif ($_POST['action'] === 'decline') {
                $new_status = 'declined';
            }
            
            if (!empty($new_status)) {
                $update_query = "UPDATE orders SET status = :status WHERE order_id = :order_id";
                $stmt = $pdo->prepare($update_query);
                $stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
                $stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $_SESSION['message'] = "Order #" . substr($order_id, -6) . " has been $new_status.";
                } else {
                    $_SESSION['error'] = "No changes made to order status. It may already be processed.";
                }
            }
            // Redirect to prevent form resubmission
            echo "<script>location.assign('orders.php')</script>";
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
            echo "<script>location.assign('orders.php')</script>";
            exit();
        }
    }
}

// Fetch all orders
try {
    $query = "SELECT * FROM orders ORDER BY date_time DESC";
    $stmt = $pdo->query($query);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<style>
/* Maintain original color scheme with layout adjustments */
body {
  font-family: Arial, sans-serif;
  margin: 20px;
}

.container-fluid {
  padding: 2rem 1.5rem;
}

.bg-light {
  background: #f8f9fa;
  border-radius: 0.5rem;
  padding: 1.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.heading {
  font-size: 2.5rem;
  font-weight: 700;
  color: #7fad39;
  margin-bottom: 1.5rem;
  text-align: left;
}

.alert {
  padding: 10px 15px;
  margin-bottom: 20px;
  border-radius: 4px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.alert-success {
  background-color: #dff0d8;
  color: #3c763d;
  border: 1px solid #d6e9c6;
}

.alert-error {
  background-color: #f2dede;
  color: #a94442;
  border: 1px solid #ebccd1;
}

.table-responsive {
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  overflow: hidden;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

th,
td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
  vertical-align: middle;
  font-size: 0.95rem;
  color: #333;
}

th {
  background-color: #343a40;
  /* Changed to match invoice_management.php table-dark */
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.9rem;
}

tr:hover {
  background-color: #f1f5f9;
  transition: background-color 0.2s ease;
}

.status-pending {
  color: #ff9800;
  font-weight: bold;
}

.status-accepted {
  color: #4caf50;
  font-weight: bold;
}

.status-declined {
  color: #f44336;
  font-weight: bold;
}

.action-buttons {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.btn {
  padding: 5px 10px;
  text-decoration: none;
  border-radius: 3px;
  font-size: 14px;
  cursor: pointer;
  border: none;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-accept {
  background-color: #4CAF50;
  color: white;
}

.btn-accept:hover {
  background-color: #45a049;
  transform: scale(1.05);
}

.btn-decline {
  background-color: #f44336;
  color: white;
}

.btn-decline:hover {
  background-color: #e53935;
  transform: scale(1.05);
}

.btn:disabled {
  background-color: #cccccc;
  color: #666666;
  cursor: not-allowed;
}

.heading {
  font-size: 2.5rem;
  font-weight: 700;
  color: #7fad39;
  margin-bottom: 1rem;
  text-align: center;
}
</style>

<!-- Order Management Start -->
<div class="container-fluid pt-4 px-4">
  <div class="row bg-light rounded mx-0">
    <div class="col-md-12">
      <h1 class="heading">Order Management</h1>

      <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message']); 
                    unset($_SESSION['message']); ?></div>
      <?php elseif (isset($_SESSION['error'])): ?>
      <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error']); 
                    unset($_SESSION['error']); ?></div>
      <?php endif; ?>

      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Product</th>
              <th>Price</th>
              <th>Qty</th>
              <th>Total</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
              <td><?php echo htmlspecialchars(substr($order['order_id'], -6)); ?></td>
              <td>
                <?php echo htmlspecialchars($order['u_name']); ?><br>
                <?php echo htmlspecialchars($order['u_email']); ?>
              </td>
              <td><?php echo htmlspecialchars($order['p_name']); ?></td>
              <td>RS <?php echo number_format($order['p_price'], 2); ?></td>
              <td><?php echo htmlspecialchars($order['p_qty']); ?></td>
              <td>Rs <?php echo number_format($order['p_price'] * $order['p_qty'], 2); ?></td>
              <td><?php echo date('M j, Y H:i', strtotime($order['date_time'])); ?></td>
              <td class="status-<?php echo htmlspecialchars($order['status']); ?>">
                <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
              </td>
              <td class="action-buttons">
                <form method="POST" style="display: inline;">
                  <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                  <button type="submit" name="action" value="accept" class="btn btn-accept"
                    <?php echo ($order['status'] !== 'pending') ? 'disabled' : ''; ?>>
                    Accept
                  </button>
                </form>
                <form method="POST" style="display: inline;">
                  <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                  <button type="submit" name="action" value="decline" class="btn btn-decline"
                    <?php echo ($order['status'] !== 'pending') ? 'disabled' : ''; ?>>
                    Decline
                  </button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Order Management End -->

<?php
include("components/footer.php");
?>