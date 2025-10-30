<?php
include("components/header.php");

// Handle order status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['order_id'])) {
        try {
            $order_id = $_POST['order_id'];
            $new_status = '';
            $decline_reason = '';
            
            if ($_POST['action'] === 'decline') {
                $new_status = 'declined';
                $decline_reason = $_POST['decline_reason'] ?? '';
                
                if (empty($decline_reason)) {
                    $_SESSION['error'] = "Please select a reason for declining the order.";
                    echo "<script>location.assign('orders.php')</script>";
                    exit();
                }
            }
            
            if (!empty($new_status)) {
                // Update order status and decline reason
                $update_query = "UPDATE orders SET status = :status, decline_reason = :decline_reason WHERE order_id = :order_id";
                $stmt = $pdo->prepare($update_query);
                $stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
                $stmt->bindParam(':decline_reason', $decline_reason, PDO::PARAM_STR);
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

.alert-info {
  background-color: #d1ecf1;
  color: #0c5460;
  border: 1px solid #bee5eb;
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
  flex-wrap: wrap;
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

/* Decline Reason Modal */
.decline-modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
}

.decline-modal-content {
  background-color: #fefefe;
  margin: 10% auto;
  padding: 20px;
  border-radius: 8px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.decline-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 1px solid #ddd;
}

.decline-modal-header h3 {
  margin: 0;
  color: #333;
}

.close-modal {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #999;
}

.close-modal:hover {
  color: #333;
}

.decline-reason-select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
  margin-bottom: 15px;
}

.decline-reason-select:focus {
  outline: none;
  border-color: #7fad39;
}

.modal-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
}

.btn-cancel {
  background-color: #6c757d;
  color: white;
}

.btn-cancel:hover {
  background-color: #5a6268;
}

.btn-confirm-decline {
  background-color: #f44336;
  color: white;
}

.btn-confirm-decline:hover {
  background-color: #e53935;
}

.decline-reason-display {
  font-size: 0.85rem;
  color: #f44336;
  font-style: italic;
  margin-top: 5px;
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

  .decline-modal-content {
    margin: 20% auto;
    width: 95%;
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

<!-- Decline Reason Modal -->
<div id="declineModal" class="decline-modal">
  <div class="decline-modal-content">
    <div class="decline-modal-header">
      <h3>Decline Order</h3>
      <button class="close-modal">&times;</button>
    </div>
    <form id="declineForm" method="POST">
      <input type="hidden" name="order_id" id="declineOrderId">
      <input type="hidden" name="action" value="decline">

      <label for="declineReason">Select reason for declining:</label>
      <select name="decline_reason" id="declineReason" class="decline-reason-select" required>
        <option value="">-- Select a reason --</option>
        <option value="Out of stock">Out of stock</option>
        <option value="Invalid address">Invalid address</option>
        <option value="Payment issue">Payment issue</option>
        <option value="Suspicious activity">Suspicious activity</option>
        <option value="Customer request">Customer request</option>
        <option value="Technical error">Technical error</option>
        <option value="Other">Other</option>
      </select>

      <div class="modal-actions">
        <button type="button" class="btn btn-cancel">Cancel</button>
        <button type="submit" class="btn btn-confirm-decline">Confirm Decline</button>
      </div>
    </form>
  </div>
</div>

<!-- Order Management Start -->
<div class="container-fluid pt-4 px-4">
  <div class="row bg-light rounded mx-0">
    <div class="col-12">
      <h1 class="heading">Order Management</h1>

      <!-- Auto-accept notice -->
      <div class="alert alert-info">
        <strong>Note:</strong> All orders are automatically accepted when placed. Use the decline button to reject
        specific orders if needed.
      </div>

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
                <?php if ($order['status'] === 'declined' && !empty($order['decline_reason'])): ?>
                <div class="decline-reason-display">
                  Reason: <?php echo htmlspecialchars($order['decline_reason']); ?>
                </div>
                <?php endif; ?>
              </td>
              <td class="action-buttons">
                <form method="POST" class="d-inline">
                  <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                  <button type="button" class="btn btn-decline decline-btn"
                    <?php echo ($order['status'] !== 'accepted') ? 'disabled' : ''; ?>
                    data-order-id="<?php echo htmlspecialchars($order['order_id']); ?>">
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

<script>
// Modal functionality
const declineModal = document.getElementById('declineModal');
const declineForm = document.getElementById('declineForm');
const declineOrderId = document.getElementById('declineOrderId');
const declineReason = document.getElementById('declineReason');
const closeModal = document.querySelector('.close-modal');
const cancelBtn = document.querySelector('.btn-cancel');
const declineBtns = document.querySelectorAll('.decline-btn');

// Open modal when decline button is clicked
declineBtns.forEach(btn => {
  btn.addEventListener('click', function() {
    if (!this.disabled) {
      const orderId = this.getAttribute('data-order-id');
      declineOrderId.value = orderId;
      declineModal.style.display = 'block';
    }
  });
});

// Close modal
closeModal.addEventListener('click', function() {
  declineModal.style.display = 'none';
  declineForm.reset();
});

cancelBtn.addEventListener('click', function() {
  declineModal.style.display = 'none';
  declineForm.reset();
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
  if (event.target === declineModal) {
    declineModal.style.display = 'none';
    declineForm.reset();
  }
});

// Form submission
declineForm.addEventListener('submit', function(e) {
  if (!declineReason.value) {
    e.preventDefault();
    alert('Please select a reason for declining the order.');
    declineReason.focus();
  }
});
</script>

<?php
include("components/footer.php");
?>