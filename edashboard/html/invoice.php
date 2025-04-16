<?php
include("components/header.php");

// Check user type
$user_type = $_SESSION['user_type'] ?? '';

// Handle approve/decline actions (only if the user is not an employee)
if (isset($_POST['update_status']) && $user_type !== 'employee') {
    $payment_id = $_POST['payment_id'];
    $new_status = $_POST['new_status'];
    
    $stmt = $pdo->prepare("UPDATE payments SET payment_status = ? WHERE payment_id = ?");
    $stmt->execute([$new_status, $payment_id]);
    
    // Show success message
    echo "<script>alert('Payment status updated successfully!');</script>";
}
?>
<style>
/* Maintain original color scheme with layout adjustments */
.container-fluid {
  padding: 2rem 1.5rem;
}

.heading {
  font-size: 2.5rem;
  font-weight: 700;
  color: #7fad39;
  margin-bottom: 1rem;
  text-align: center;
}

.bg-light {
  background: #f8f9fa;
  border-radius: 0.5rem;
  padding: 1.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}



.table-responsive {
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  overflow: hidden;
}

.table {
  margin-bottom: 0;
}

.table thead th {
  background: #343a40;
  /* Original table-dark background */
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.9rem;
  padding: 1rem;
  border: none;
}

.table td {
  vertical-align: middle;
  padding: 1rem;
  font-size: 0.95rem;
  color: #333;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f8f9fc;
}

.badge {
  padding: 0.5em 1em;
  font-size: 0.85rem;
  border-radius: 0.25rem;
}

.btn-sm {
  padding: 0.35rem 0.75rem;
  font-size: 0.85rem;
  border-radius: 0.25rem;
  transition: all 0.2s ease;
}

.btn-info {
  background: #17a2b8;
  border: none;
}

.btn-info:hover {
  background: #138496;
}

.btn-success {
  background: #28a745;
  border: none;
}

.btn-success:hover {
  background: #218838;
}

.btn-danger {
  background: #dc3545;
  border: none;
}

.btn-danger:hover {
  background: #c82333;
}

.alert-warning {
  background: #fff3cd;
  border: none;
  border-radius: 0.5rem;
  padding: 1rem;
  margin-bottom: 1.5rem;
  color: #856404;
}

.modal-content {
  border: none;
  border-radius: 0.5rem;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.modal-header {
  background: #343a40;
  color: white;
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
}

.modal-title {
  font-weight: 600;
}

.modal-body {
  padding: 2rem;
}

.modal-body h6 {
  color: #7fad39;
  font-weight: 600;
  margin-bottom: 1rem;
}

.modal-footer {
  border-top: none;
  padding: 1rem 2rem;
}

.btn-secondary {
  background: #6c757d;
  border: none;
}

.btn-secondary:hover {
  background: #5a6268;
}

/* Adjusted layout for action buttons */
.action-buttons {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.table tbody tr:hover {
  background-color: #f1f5f9;
  transition: background-color 0.2s ease;
}
</style>

<!-- Invoice Management Start -->
<div class="container-fluid pt-4 px-4">
  <?php if ($user_type === 'employee'): ?>
  <div class="alert alert-warning" role="alert">
    You do not have permission to approve or decline payments. All actions are disabled.
  </div>
  <?php endif; ?>

  <div class="row bg-light rounded mx-0">
    <div class="col-md-12">
      <h3 class="heading">Payment Invoices</h3>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Invoice ID</th>
              <th>Date</th>
              <th>Customer</th>
              <th>Method</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Details</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
                        $query = $pdo->query("SELECT * FROM payments ORDER BY payment_date DESC");
                        $payments = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($payments as $payment) {
                            $status_class = '';
                            switch ($payment['payment_status']) {
                                case 'completed':
                                    $status_class = 'bg-success';
                                    break;
                                case 'failed':
                                    $status_class = 'bg-danger';
                                    break;
                                default:
                                    $status_class = 'bg-warning';
                            }
                        ?>
            <tr>
              <td>#<?php echo $payment['payment_id'] ?></td>
              <td><?php echo date('M d, Y h:i A', strtotime($payment['payment_date'])) ?></td>
              <td>
                <?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']) ?><br>
                <small><?php echo htmlspecialchars($payment['email']) ?></small>
              </td>
              <td><?php echo htmlspecialchars($payment['payment_method']) ?></td>
              <td>Rs <?php echo number_format($payment['amount'], 2) ?></td>
              <td>
                <span class="badge <?php echo $status_class ?>">
                  <?php echo ucfirst($payment['payment_status']) ?>
                </span>
              </td>
              <td>
                <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                  data-bs-target="#detailsModal<?php echo $payment['payment_id'] ?>">
                  View
                </button>
              </td>
              <td>
                <?php if ($payment['payment_status'] == 'pending') { ?>
                <div class="action-buttons">
                  <form method="post" class="d-inline">
                    <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id'] ?>">
                    <input type="hidden" name="new_status" value="completed">
                    <button type="submit" name="update_status" class="btn btn-sm btn-success"
                      <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>Approve</button>
                  </form>
                  <form method="post" class="d-inline">
                    <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id'] ?>">
                    <input type="hidden" name="new_status" value="failed">
                    <button type="submit" name="update_status" class="btn btn-sm btn-danger"
                      <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>Decline</button>
                  </form>
                </div>
                <?php } else { ?>
                <span class="text-muted">Processed</span>
                <?php } ?>
              </td>
            </tr>

            <!-- Details Modal -->
            <div class="modal fade" id="detailsModal<?php echo $payment['payment_id'] ?>" tabindex="-1"
              aria-labelledby="detailsModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Invoice #<?php echo $payment['payment_id'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p>
                          <strong>Name:</strong>
                          <?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']) ?><br>
                          <strong>Email:</strong> <?php echo htmlspecialchars($payment['email']) ?><br>
                          <strong>Phone:</strong> <?php echo htmlspecialchars($payment['phone']) ?><br>
                          <strong>Address:</strong><br>
                          <?php echo htmlspecialchars($payment['address']) ?><br>
                          <?php echo htmlspecialchars($payment['city'] . ', ' . $payment['state']) ?><br>
                          <?php echo htmlspecialchars($payment['postcode'] . ', ' . $payment['country']) ?>
                        </p>
                      </div>
                      <div class="col-md-6">
                        <h6>Payment Details</h6>
                        <p>
                          <strong>Method:</strong> <?php echo htmlspecialchars($payment['payment_method']) ?><br>
                          <strong>Amount:</strong> Rs <?php echo number_format($payment['amount'], 2) ?><br>
                          <strong>Date:</strong>
                          <?php echo date('M d, Y h:i A', strtotime($payment['payment_date'])) ?><br>
                          <strong>Status:</strong> <span
                            class="badge <?php echo $status_class ?>"><?php echo ucfirst($payment['payment_status']) ?></span>
                        </p>

                        <?php if ($payment['payment_method'] == 'Credit Card') { ?>
                        <h6>Card Details</h6>
                        <p>
                          <strong>Card:</strong> **** **** **** <?php echo substr($payment['card_number'], -4) ?><br>
                          <strong>Expires:</strong> <?php echo htmlspecialchars($payment['expiry_date']) ?><br>
                          <strong>CVV:</strong> ***
                        </p>
                        <?php } elseif ($payment['payment_method'] == 'Bank Transfer') { ?>
                        <h6>Bank Details</h6>
                        <p>
                          <strong>Bank:</strong> <?php echo htmlspecialchars($payment['bank_name']) ?><br>
                          <strong>Check #:</strong> <?php echo htmlspecialchars($payment['check_number']) ?>
                        </p>
                        <?php } ?>
                      </div>
                    </div>

                    <div class="mt-3">
                      <h6>Order Notes</h6>
                      <p><?php echo htmlspecialchars($payment['order_notes']) ?: 'No notes provided' ?></p>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <?php if ($payment['payment_status'] == 'pending' && $user_type !== 'employee') { ?>
                    <form method="post" class="d-inline">
                      <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id'] ?>">
                      <input type="hidden" name="new_status" value="completed">
                      <button type="submit" name="update_status" class="btn btn-success">Approve Payment</button>
                    </form>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Invoice Management End -->

<?php
include("components/footer.php");
?>