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
/* Responsive styling with original color scheme */
.container-fluid {
  padding: clamp(1rem, 3vw, 2rem) clamp(0.75rem, 2vw, 1.5rem);
}

.heading {
  font-size: clamp(1.8rem, 5vw, 2.5rem);
  font-weight: 700;
  color: #7fad39;
  margin-bottom: 1.5rem;
  text-align: center;
}

.bg-light {
  background: #f8f9fa;
  border-radius: 0.5rem;
  padding: clamp(1rem, 2vw, 1.5rem);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.alert {
  padding: clamp(0.5rem, 1vw, 0.75rem) clamp(0.75rem, 1.5vw, 1rem);
  margin-bottom: 1rem;
  border-radius: 0.25rem;
  font-size: clamp(0.75rem, 1vw, 0.85rem);
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
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
  min-width: 700px;
  /* Ensures all columns are accessible */
}

.table th,
.table td {
  padding: clamp(0.5rem, 1vw, 0.75rem);
  text-align: left;
  border-bottom: 1px solid #ddd;
  vertical-align: middle;
  font-size: clamp(0.75rem, 1vw, 0.85rem);
  color: #333;
}

.table th {
  background-color: #343a40;
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  font-size: clamp(0.7rem, 0.9vw, 0.8rem);
}

.table tr:hover {
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
  padding: clamp(0.3rem, 0.8vw, 0.5rem) clamp(0.5rem, 1vw, 0.75rem);
  font-size: clamp(0.7rem, 1vw, 0.8rem);
  text-decoration: none;
  border-radius: 0.25rem;
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
  transform: none;
}

/* Scrollbar styling */
.table-responsive::-webkit-scrollbar {
  height: 8px;
}

.table-responsive::-webkit-scrollbar-thumb {
  background: #7fad39;
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .heading {
    font-size: clamp(1.5rem, 4vw, 2rem);
  }

  .table {
    min-width: 600px;
  }

  .table th,
  .table td {
    padding: clamp(0.4rem, 0.8vw, 0.6rem);
    font-size: clamp(0.7rem, 0.9vw, 0.8rem);
  }

  .action-buttons {
    flex-direction: column;
    align-items: start;
  }

  .btn {
    width: 100%;
    text-align: center;
    padding: clamp(0.25rem, 0.6vw, 0.4rem) clamp(0.4rem, 0.8vw, 0.6rem);
    font-size: clamp(0.65rem, 0.9vw, 0.75rem);
  }
}

@media (max-width: 576px) {
  .container-fluid {
    padding: clamp(0.5rem, 1.5vw, 0.75rem);
  }

  .table {
    min-width: 500px;
  }

  .table th,
  .table td {
    padding: clamp(0.3rem, 0.6vw, 0.4rem);
    font-size: clamp(0.65rem, 0.8vw, 0.7rem);
  }

  .btn {
    padding: clamp(0.2rem, 0.5vw, 0.3rem) clamp(0.3rem, 0.6vw, 0.5rem);
    font-size: clamp(0.6rem, 0.8vw, 0.7rem);
  }

  .alert {
    font-size: clamp(0.7rem, 0.9vw, 0.8rem);
  }
}
</style>

<!-- Order Management Start -->
<div class="container-fluid pt-4 px-4">
  <div class="row bg-light rounded mx-0">
    <div class="col-12">
      <h1 class="heading">Order Management</h1>

      <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php elseif (isset($_SESSION['error'])): ?>
      <div class="alert alert-error alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table">
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
                <small><?php echo htmlspecialchars($order['u_email']); ?></small>
              </td>
              <td><?php echo htmlspecialchars($order['p_name']); ?></td>
              <td>Rs <?php echo number_format($order['p_price'], 2); ?></td>
              <td><?php echo htmlspecialchars($order['p_qty']); ?></td>
              <td>Rs <?php echo number_format($order['p_price'] * $order['p_qty'], 2); ?></td>
              <td><?php echo date('M j, Y H:i', strtotime($order['date_time'])); ?></td>
              <td class="status-<?php echo htmlspecialchars($order['status']); ?>">
                <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
              </td>
              <td class="action-buttons">
                <form method="POST" class="d-inline">
                  <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                  <button type="submit" name="action" value="accept" class="btn btn-accept"
                    <?php echo ($order['status'] !== 'pending') ? 'disabled' : ''; ?>>
                    Accept
                  </button>
                </form>
                <form method="POST" class="d-inline">
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