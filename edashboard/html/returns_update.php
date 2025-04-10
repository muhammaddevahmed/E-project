<style>
/* Base Styles */
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  margin: 0;
  padding: 20px;
  background-color: #f5f7fa;
  color: #f5f7fa;
  line-height: 1.6;
}

h1 {
  color: #2c3e50;
  margin-bottom: 25px;
  padding-bottom: 10px;
  border-bottom: 1px solid #eaeaea;

  text-align: center;
}

/* Alert Messages */
.alert {
  padding: 12px 15px;
  margin-bottom: 20px;
  border-radius: 4px;
  font-size: 14px;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert-error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

/* Table Styles */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  background-color: white;
}

th,
td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #e0e0e0;
}

th {
  background-color: #f8f9fa;
  font-weight: 600;
  color: #495057;
  text-transform: uppercase;
  font-size: 13px;
  letter-spacing: 0.5px;
}

tr:hover {
  background-color: #f8f9fa;
}

/* Status Badges */
.status-pending {
  color: #ff9800;
  font-weight: 600;
  background-color: #fff3e0;
  padding: 5px 10px;
  border-radius: 12px;
  display: inline-block;
  font-size: 13px;
}

.status-approved {
  color: #4caf50;
  font-weight: 600;
  background-color: #e8f5e9;
  padding: 5px 10px;
  border-radius: 12px;
  display: inline-block;
  font-size: 13px;
}

.status-rejected {
  color: #f44336;
  font-weight: 600;
  background-color: #ffebee;
  padding: 5px 10px;
  border-radius: 12px;
  display: inline-block;
  font-size: 13px;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 8px;
}

/* Action Buttons - Refined Color Scheme */
.btn-accept {
  background-color: #28a745;
  /* Fresh green */
  color: white;
  border: 1px solid #218838;
  /* Slightly darker green */
}

.btn-accept:hover {
  background-color: #218838;
  box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.btn-decline {
  background-color: #dc3545;
  /* Vibrant red */
  color: white;
  border: 1px solid #c82333;
  /* Slightly darker red */
}

.btn-decline:hover {
  background-color: #c82333;
  box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

/* Disabled State */
.btn:disabled {
  background-color: #e9ecef;
  color: #6c757d;
  border: 1px solid #dee2e6;
  cursor: not-allowed;
  box-shadow: none;
}

/* Active State (when clicked) */
.btn-accept:active {
  background-color: #1e7e34;
  transform: translateY(1px);
}

.btn-decline:active {
  background-color: #bd2130;
  transform: translateY(1px);
}

.btn:disabled {
  background-color: #e0e0e0;
  color: #9e9e9e;
  cursor: not-allowed;
}

/* Product Image */
.product-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 4px;
  border: 1px solid #e0e0e0;
  margin-right: 10px;
}

/* Customer Info */
.customer-info {
  font-size: 14px;
  line-height: 1.4;
}

.customer-name {
  font-weight: 600;
  margin-bottom: 3px;
}

.customer-email {
  color: #666;
  font-size: 13px;
}

/* Reason Text */
.reason-text {
  max-width: 300px;
  white-space: pre-wrap;
  word-break: break-word;
  font-size: 14px;
  color: #555;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  table {
    display: block;
    overflow-x: auto;
  }

  .action-buttons {
    flex-direction: column;
    gap: 5px;
  }

  .btn {
    width: 100%;
    padding: 8px 5px;
  }
}

/* Hover Effects */
.btn:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Table Row Animation */
tr {
  transition: background-color 0.2s ease;
}

/* Zebra Striping (optional) */
tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}

/* Date Formatting */
.date-cell {
  white-space: nowrap;
  font-size: 13px;
  color: #666;
}

/* ID Formatting */
.id-cell {
  font-family: 'Courier New', monospace;
  font-weight: 600;
  color: #2c3e50;
}

/* Quantity Formatting */
.quantity-cell {
  text-align: center;
  font-weight: 600;
}
</style>
<?php
include("components/header.php");

// Handle return status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['return_id'])) {
        try {
            $return_id = $_POST['return_id'];
            $new_status = '';
            
            if ($_POST['action'] === 'accept') {
                $new_status = 'approved';
            } elseif ($_POST['action'] === 'decline') {
                $new_status = 'rejected';
            }
            
            if (!empty($new_status)) {
                $update_query = "UPDATE returns SET return_status = :status WHERE return_id = :return_id";
                $stmt = $pdo->prepare($update_query);
                $stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
                $stmt->bindParam(':return_id', $return_id, PDO::PARAM_INT);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $_SESSION['message'] = "Return #$return_id has been $new_status.";
                    
                    // If approved, update product stock
                    if ($new_status === 'approved') {
                        // Get product details from the return
                        $get_query = "SELECT r.product_id, o.p_qty 
                                     FROM returns r
                                     JOIN orders o ON r.order_id = o.order_id
                                     WHERE r.return_id = :return_id";
                        $stmt = $pdo->prepare($get_query);
                        $stmt->bindParam(':return_id', $return_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $return_details = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($return_details) {
                            // Update product stock
                            $update_stock = "UPDATE products 
                                           SET stock_quantity = stock_quantity + :qty 
                                           WHERE product_id = :product_id";
                            $stmt = $pdo->prepare($update_stock);
                            $stmt->bindParam(':qty', $return_details['p_qty'], PDO::PARAM_INT);
                            $stmt->bindParam(':product_id', $return_details['product_id'], PDO::PARAM_STR);
                            $stmt->execute();
                        }
                    }
                } else {
                    $_SESSION['error'] = "No changes made to return status. It may already be processed.";
                }
            }
            // Redirect to prevent form resubmission
            echo "<script>location.assign('returns_update.php')
          </script>";
            exit();
        } catch (PDOException $e) {
          $_SESSION['error'] = "Database error: " . $e->getMessage();
          echo "<script>location.assign('returns_update.php')
          </script>";
          exit();
      }
    }
}

// Fetch all returns with their details
try {
    $query = "SELECT r.*, p.product_name, o.u_name, o.u_email, o.p_qty, o.p_price
              FROM returns r
              JOIN products p ON r.product_id = p.product_id
              JOIN orders o ON r.order_id = o.order_id
              ORDER BY r.return_date DESC";
    $stmt = $pdo->query($query);
    $returns = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>



<body>
  <h1>Returns Management</h1>

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
        <th>Return ID</th>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Reason</th>
        <th>Date</th>
        <th>Status</th>
        <!-- <th>Actions</th> -->
      </tr>
    </thead>
    <tbody>
      <?php foreach ($returns as $return): ?>
      <tr>
        <td><?php echo htmlspecialchars($return['return_id']); ?></td>
        <td><?php echo htmlspecialchars(substr($return['order_id'], -6)); ?></td>
        <td>
          <?php echo htmlspecialchars($return['u_name']); ?><br>
          <?php echo htmlspecialchars($return['u_email']); ?>
        </td>
        <td><?php echo htmlspecialchars($return['product_name']); ?></td>
        <td><?php echo htmlspecialchars($return['p_qty']); ?></td>
        <td><?php echo nl2br(htmlspecialchars($return['reason'])); ?></td>
        <td><?php echo date('M j, Y H:i', strtotime($return['return_date'])); ?></td>
        <td class="status-<?php echo htmlspecialchars($return['return_status']); ?>">
          <?php echo ucfirst(htmlspecialchars($return['return_status'])); ?>
        </td>
        <td class="action-buttons">
          <form method="POST" style="display: inline;">
            <input type="hidden" name="return_id" value="<?php echo htmlspecialchars($return['return_id']); ?>">
            <button type="submit" name="action" value="accept"
              style="color: white; background-color: #28a745; border: none; padding: 10px 18px; border-radius: 4px; font-weight: 600; cursor: pointer;"
              <?php echo ($return['return_status'] !== 'pending') ? 'disabled' : ''; ?>>
              Approve
            </button>

          </form>
          <form method="POST" style="display: inline;">
            <input type="hidden" name="return_id" value="<?php echo htmlspecialchars($return['return_id']); ?>">
            <button type="submit" name="action" value="decline" class="btn btn-decline"
              style="color: white; background-color: #f44336; border: none; padding: 8px 15px; border-radius: 4px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.3s ease;"
              <?php echo ($return['return_status'] !== 'pending') ? 'disabled' : ''; ?>>
              Reject
            </button>

          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php
include("components/footer.php");
  ?>