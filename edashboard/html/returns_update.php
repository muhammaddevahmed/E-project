<?php
include("components/header.php");
$user_type = $_SESSION['user_type'] ?? '';

// Handle action updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['return_id'])) {
        try {
            $return_id = $_POST['return_id'];
            $new_status = ($_POST['action'] == 'approve') ? 'approved' : 'rejected';

            // Update the return_status in the database
            $update_query = "UPDATE returns SET return_status = :status WHERE return_id = :return_id";
            $stmt = $pdo->prepare($update_query);
            $stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
            $stmt->bindParam(':return_id', $return_id, PDO::PARAM_INT);
            $stmt->execute();

            // Refresh the page to show updated status
            echo "<script>location.assign('returns_update.php');</script>";
            exit();
        } catch (PDOException $e) {
            die("Error updating return status: " . $e->getMessage());
        }
    }
}

// Fetch all records from the returns table
$returns = []; // Initialize as an empty array
try {
    $query = "SELECT * FROM returns ORDER BY return_date DESC";
    $stmt = $pdo->query($query);
    $returns = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching records: " . $e->getMessage());
}
?>


<style>
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

.btn-accept {
  background-color: #28a745;
  color: white;
  border: 1px solid #218838;
  padding: 8px 15px;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-accept:hover {
  background-color: #218838;
  box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.btn-decline {
  background-color: #dc3545;
  color: white;
  border: 1px solid #c82333;
  padding: 8px 15px;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-decline:hover {
  background-color: #c82333;
  box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.btn:disabled {
  background-color: #e9ecef;
  color: #6c757d;
  border: 1px solid #dee2e6;
  cursor: not-allowed;
  box-shadow: none;
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
</style>
</head>


<h1>Returns Management</h1>

<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
</div>
<?php elseif (isset($_SESSION['error'])): ?>
<div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php endif; ?>

<table>
  <thead>
    <tr>
      <th>Return ID</th>
      <th>Order ID</th>
      <th>Product ID</th>
      <th>Reason</th>
      <th>Return Date</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($returns)): ?>
    <?php foreach ($returns as $return): ?>
    <tr>
      <td><?php echo htmlspecialchars($return['return_id']); ?></td>
      <td><?php echo htmlspecialchars($return['order_id']); ?></td>
      <td><?php echo htmlspecialchars($return['product_id']); ?></td>
      <td><?php echo nl2br(htmlspecialchars($return['reason'])); ?></td>
      <td><?php echo date('M j, Y H:i', strtotime($return['return_date'])); ?></td>
      <td>
        <span class="status-<?php echo htmlspecialchars($return['return_status']); ?>">
          <?php echo ucfirst(htmlspecialchars($return['return_status'])); ?>
        </span>
      </td>
      <td class="action-buttons">
        <?php if ($return['return_status'] === 'pending'): ?>
        <form method="POST" style="display: inline;">
          <input type="hidden" name="return_id" value="<?php echo htmlspecialchars($return['return_id']); ?>">
          <button type="submit" name="action" value="approve" class="btn-accept">Approve</button>
        </form>
        <form method="POST" style="display: inline;">
          <input type="hidden" name="return_id" value="<?php echo htmlspecialchars($return['return_id']); ?>">
          <button type="submit" name="action" value="reject" class="btn-decline">Reject</button>
        </form>
        <?php else: ?>
        <span class="text-muted">Action completed</span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
      <td colspan="7" style="text-align: center;">No records found.</td>
    </tr>
    <?php endif; ?>
  </tbody>
</table>