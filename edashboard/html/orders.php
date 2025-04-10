<style>
body {
  font-family: Arial, sans-serif;
  margin: 20px;
}

h1 {
  color: #2c3e50;
  margin-bottom: 30px;
  text-align: center;
}

.alert {
  padding: 10px 15px;
  margin-bottom: 20px;
  border-radius: 4px;
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
}

th {
  background-color: #f2f2f2;
  font-weight: bold;
}

tr:hover {
  background-color: #f5f5f5;
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
  gap: 5px;
}

.btn {
  padding: 5px 10px;
  text-decoration: none;
  border-radius: 3px;
  font-size: 14px;
  cursor: pointer;
  border: none;
}

.btn-accept {
  background-color: #4CAF50;
  color: white;
}

.btn-decline {
  background-color: #f44336;
  color: white;
}

.btn:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}
</style>
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
         echo "<script>location.assign('orders.php')
         </script>";
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
            echo "<script>location.assign('orders.php')
            </script>";
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





<body>
  <h1>Order Management</h1>

  <?php if (isset($_SESSION['message'])): ?>
  <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message']); 
        unset($_SESSION['message']); ?></div>
  <?php elseif (isset($_SESSION['error'])): ?>
  <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error']); 
        unset($_SESSION['error']); ?></div>
  <?php endif; ?>

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
        <td>$<?php echo number_format($order['p_price'], 2); ?></td>
        <td><?php echo htmlspecialchars($order['p_qty']); ?></td>
        <td>$<?php echo number_format($order['p_price'] * $order['p_qty'], 2); ?></td>
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
</body>