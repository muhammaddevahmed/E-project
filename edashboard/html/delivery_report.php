<?php
include("components/header.php");





// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['update_delivery'])) {
            // Update delivery status or date
            $delivery_id = filter_input(INPUT_POST, 'delivery_id', FILTER_SANITIZE_NUMBER_INT);
            $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
            $delivery_date = filter_input(INPUT_POST, 'delivery_date', FILTER_SANITIZE_STRING);
            $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);

            // Determine which date to update
            $date_field = ($status == 'delivered') ? 'actual_delivery_date' : 'estimated_delivery_date';

            $query = "UPDATE deliveries SET 
                      delivery_status = :status, 
                      $date_field = :delivery_date,
                      delivery_notes = :notes
                      WHERE delivery_id = :delivery_id";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':status' => $status,
                ':delivery_date' => $delivery_date,
                ':notes' => $notes,
                ':delivery_id' => $delivery_id
            ]);

            // Log status change
            $history_query = "INSERT INTO delivery_status_history 
                             (delivery_id, old_status, new_status, changed_by, change_notes)
                             VALUES (:delivery_id, :old_status, :new_status, :changed_by, :notes)";
            $history_stmt = $pdo->prepare($history_query);
            $history_stmt->execute([
                ':delivery_id' => $delivery_id,
                ':old_status' => $_POST['old_status'],
                ':new_status' => $status,
                ':changed_by' => $_SESSION['user_id'],
                ':notes' => "Status updated via delivery report"
            ]);

            $_SESSION['message'] = "Delivery #$delivery_id updated successfully!";
            $_SESSION['message_type'] = 'success';
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error updating delivery: " . $e->getMessage();
        $_SESSION['message_type'] = 'danger';
    }
    header("Location: delivery_report.php");
    exit();
}

// Fetch all deliveries
try {
    $query = "SELECT d.*, 
              o.date_time, 
              c.username, 
              c.phone,
              c.email,
              p.product_name,
              p.image_path
              FROM deliveries d
              JOIN orders o ON d.order_id = o.order_id
              JOIN users c ON d.user_id = c.user_id
              JOIN products p ON d.product_id = p.product_id
              ORDER BY d.created_at DESC";

    $deliveries = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching deliveries: " . $e->getMessage());
}
?>

<style>
.delivery-card {
  transition: all 0.3s ease;
  border-left: 4px solid;
}

.delivery-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.status-pending {
  border-left-color: #ffc107;
}

.status-processing {
  border-left-color: #17a2b8;
}

.status-shipped {
  border-left-color: #007bff;
}

.status-delivered {
  border-left-color: #28a745;
}

.status-cancelled {
  border-left-color: #dc3545;
}

.product-img {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 4px;
}

.badge-status {
  padding: 0.5em 0.75em;
  font-size: 0.8em;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.date-input-group {
  max-width: 250px;
}
</style>
< <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Include sidebar/menu -->

      <div class="layout-page">
        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">
              <span class="text-muted fw-light">Delivery /</span> Management Report
            </h4>

            <!-- Display messages -->
            <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
              <?= $_SESSION['message'] ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php 
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                        endif; ?>

            <!-- Delivery Report Cards -->
            <div class="row mb-4">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Delivery Statistics</h5>
                    <?php
                                        try {
                                            $stats_query = "SELECT 
                                                COUNT(*) as total,
                                                SUM(CASE WHEN delivery_status = 'pending' THEN 1 ELSE 0 END) as pending,
                                                SUM(CASE WHEN delivery_status = 'processing' THEN 1 ELSE 0 END) as processing,
                                                SUM(CASE WHEN delivery_status = 'shipped' THEN 1 ELSE 0 END) as shipped,
                                                SUM(CASE WHEN delivery_status = 'delivered' THEN 1 ELSE 0 END) as delivered
                                                FROM deliveries";
                                            $stats = $pdo->query($stats_query)->fetch(PDO::FETCH_ASSOC);
                                        } catch (PDOException $e) {
                                            $stats = ['total' => 0, 'pending' => 0, 'processing' => 0, 'shipped' => 0, 'delivered' => 0];
                                        }
                                        ?>
                    <div class="delivery-stats">
                      <div class="stat-item">
                        <span class="stat-number"><?= $stats['total'] ?></span>
                        <span class="stat-label">Total Deliveries</span>
                      </div>
                      <div class="stat-item text-warning">
                        <span class="stat-number"><?= $stats['pending'] ?></span>
                        <span class="stat-label">Pending</span>
                      </div>
                      <div class="stat-item text-info">
                        <span class="stat-number"><?= $stats['processing'] ?></span>
                        <span class="stat-label">Processing</span>
                      </div>
                      <div class="stat-item text-primary">
                        <span class="stat-number"><?= $stats['shipped'] ?></span>
                        <span class="stat-label">Shipped</span>
                      </div>
                      <div class="stat-item text-success">
                        <span class="stat-number"><?= $stats['delivered'] ?></span>
                        <span class="stat-label">Delivered</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Upcoming Deliveries</h5>
                    <?php
                                        try {
                                            $upcoming_query = "SELECT COUNT(*) as count 
                                                FROM deliveries 
                                                WHERE estimated_delivery_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                                                AND delivery_status NOT IN ('delivered', 'cancelled')";
                                            $upcoming = $pdo->query($upcoming_query)->fetchColumn();
                                        } catch (PDOException $e) {
                                            $upcoming = 0;
                                        }
                                        ?>
                    <div class="upcoming-deliveries">
                      <span class="display-4"><?= $upcoming ?></span>
                      <p>deliveries scheduled in the next 7 days</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Delivery List -->
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Deliveries</h5>
                <div class="dropdown">
                  <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Filter by Status
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item" href="?status=all">All Deliveries</a></li>
                    <li><a class="dropdown-item" href="?status=pending">Pending</a></li>
                    <li><a class="dropdown-item" href="?status=processing">Processing</a></li>
                    <li><a class="dropdown-item" href="?status=shipped">Shipped</a></li>
                    <li><a class="dropdown-item" href="?status=delivered">Delivered</a></li>
                  </ul>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Delivery ID</th>
                      <th>Order Details</th>
                      <th>Customer</th>
                      <th>Product</th>
                      <th>Dates</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($deliveries as $delivery): ?>
                    <tr class="status-<?= $delivery['delivery_status'] ?>">
                      <td>#<?= $delivery['delivery_id'] ?></td>
                      <td>
                        <strong>Order #<?= $delivery['order_id'] ?></strong><br>
                        <small><?= date('M d, Y', strtotime($delivery['order_date'])) ?></small>
                      </td>
                      <td>
                        <?= $delivery['customer_name'] ?><br>
                        <small><?= $delivery['customer_phone'] ?></small>
                      </td>
                      <td>
                        <div class="d-flex align-items-center">
                          <img src="../../website/images/products/<?= $delivery['product_image'] ?>"
                            alt="<?= $delivery['product_name'] ?>" class="product-img me-3">
                          <?= $delivery['product_name'] ?>
                        </div>
                      </td>
                      <td>
                        <?php if ($delivery['estimated_delivery_date']): ?>
                        <strong>Est:</strong>
                        <?= date('M d, Y', strtotime($delivery['estimated_delivery_date'])) ?><br>
                        <?php endif; ?>
                        <?php if ($delivery['actual_delivery_date']): ?>
                        <strong>Actual:</strong> <?= date('M d, Y', strtotime($delivery['actual_delivery_date'])) ?>
                        <?php endif; ?>
                      </td>
                      <td>
                        <span class="badge badge-status bg-<?= 
                                                    $delivery['delivery_status'] == 'pending' ? 'warning' : 
                                                    ($delivery['delivery_status'] == 'processing' ? 'info' : 
                                                    ($delivery['delivery_status'] == 'shipped' ? 'primary' : 
                                                    ($delivery['delivery_status'] == 'delivered' ? 'success' : 'danger')))
                                                ?>">
                          <?= ucfirst($delivery['delivery_status']) ?>
                        </span>
                      </td>
                      <td>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                          data-bs-target="#editDeliveryModal<?= $delivery['delivery_id'] ?>">
                          <i class="bx bx-edit"></i> Update
                        </button>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!--/ Delivery List -->
          </div>
          <!-- / Content -->
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>
  </div>

  <!-- Edit Delivery Modals -->
  <?php foreach ($deliveries as $delivery): ?>
  <div class="modal fade" id="editDeliveryModal<?= $delivery['delivery_id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Update Delivery #<?= $delivery['delivery_id'] ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="delivery_report.php">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Order ID</label>
                  <input type="text" class="form-control" value="<?= $delivery['order_id'] ?>" readonly>
                </div>
                <div class="mb-3">
                  <label class="form-label">Customer</label>
                  <input type="text" class="form-control" value="<?= $delivery['customer_name'] ?>" readonly>
                </div>
                <div class="mb-3">
                  <label class="form-label">Product</label>
                  <input type="text" class="form-control" value="<?= $delivery['product_name'] ?>" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Current Status</label>
                  <input type="text" class="form-control" value="<?= ucfirst($delivery['delivery_status']) ?>" readonly>
                </div>
                <div class="mb-3">
                  <label class="form-label">Update Status</label>
                  <select class="form-select" name="status" required>
                    <option value="pending" <?= $delivery['delivery_status'] == 'pending' ? 'selected' : '' ?>>Pending
                    </option>
                    <option value="processing" <?= $delivery['delivery_status'] == 'processing' ? 'selected' : '' ?>>
                      Processing</option>
                    <option value="shipped" <?= $delivery['delivery_status'] == 'shipped' ? 'selected' : '' ?>>Shipped
                    </option>
                    <option value="delivered" <?= $delivery['delivery_status'] == 'delivered' ? 'selected' : '' ?>>
                      Delivered</option>
                    <option value="cancelled" <?= $delivery['delivery_status'] == 'cancelled' ? 'selected' : '' ?>>
                      Cancelled</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Delivery Date</label>
                  <input type="date" class="form-control flatpickr-date" name="delivery_date"
                    value="<?= $delivery['delivery_status'] == 'delivered' ? $delivery['actual_delivery_date'] : $delivery['estimated_delivery_date'] ?>"
                    required>
                </div>
              </div>
              <div class="col-12">
                <div class="mb-3">
                  <label class="form-label">Notes</label>
                  <textarea class="form-control" name="notes" rows="3"><?= $delivery['delivery_notes'] ?></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="delivery_id" value="<?= $delivery['delivery_id'] ?>">
            <input type="hidden" name="old_status" value="<?= $delivery['delivery_status'] ?>">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="update_delivery" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>


  <script>
  // Initialize date pickers
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize all date inputs
    flatpickr('.flatpickr-date', {
      dateFormat: "Y-m-d",
      minDate: "today"
    });

    // Status change event to update date label
    document.querySelectorAll('select[name="status"]').forEach(select => {
      select.addEventListener('change', function() {
        const dateLabel = this.closest('.modal-content').querySelector('label[for="delivery_date"]');
        if (this.value === 'delivered') {
          dateLabel.textContent = 'Actual Delivery Date';
        } else {
          dateLabel.textContent = 'Estimated Delivery Date';
        }
      });
    });
  });
  </script>