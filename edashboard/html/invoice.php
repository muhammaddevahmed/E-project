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

<!-- Invoice Management Start -->
<div class="container-fluid pt-4 px-4">
  <?php if ($user_type === 'employee'): ?>
  <div class="alert alert-warning" role="alert">
    You do not have permission to approve or decline payments. All actions are disabled.
  </div>
  <?php endif; ?>

  <div class="row bg-light rounded mx-0">
    <div class="col-md-12">
      <h3>Payment Invoices</h3>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead class="table-dark">
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
              <td>$<?php echo number_format($payment['amount'], 2) ?></td>
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
                          <strong>Amount:</strong> $<?php echo number_format($payment['amount'], 2) ?><br>
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