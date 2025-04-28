<?php
include("components/header.php");

// Fetch all orders with delivery information
$stmt = $pdo->prepare("
    SELECT o.*, d.delivery_id, d.estimated_delivery_date, 
           d.actual_delivery_date, d.delivery_notes 
    FROM orders o 
    LEFT JOIN deliveries d ON o.order_id = d.order_id
    ORDER BY o.date_time DESC
");
$stmt->execute();
$orders = $stmt->fetchAll();
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
  /* Ensures all columns are accessible via scroll */
}

.table th,
.table td {
  padding: clamp(0.5rem, 1vw, 0.75rem);
  border: 1px solid #ddd;
  text-align: left;
  font-size: clamp(0.75rem, 1vw, 0.85rem);
  vertical-align: middle;
}

.table th {
  background-color: #f2f2f2;
  position: sticky;
  top: 0;
  font-weight: 600;
  text-transform: uppercase;
}

.table tr:nth-child(even) {
  background-color: #f9f9f9;
}

.table tr:hover {
  background-color: #f1f1f1;
}

.status-pending {
  background-color: #fff3cd;
}

.status-processing {
  background-color: #cce5ff;
}

.status-shipped {
  background-color: #d4edda;
}

.status-delivered {
  background-color: #d1e7dd;
}

.btn {
  padding: clamp(0.3rem, 0.8vw, 0.5rem) clamp(0.5rem, 1vw, 0.75rem);
  font-size: clamp(0.7rem, 1vw, 0.8rem);
  cursor: pointer;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 0.25rem;
  transition: background-color 0.2s ease;
}

.btn:hover {
  background-color: #45a049;
}

.btn:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
  opacity: 0.7;
}

.form-control {
  width: 100%;
  padding: clamp(0.4rem, 1vw, 0.6rem);
  font-size: clamp(0.75rem, 1vw, 0.85rem);
  box-sizing: border-box;
  border: 1px solid #ddd;
  border-radius: 0.25rem;
}

.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: clamp(5%, 10vw, 10%) auto;
  padding: clamp(1rem, 2vw, 1.5rem);
  border: 1px solid #888;
  width: clamp(90%, 95vw, 50%);
  max-width: 600px;
  border-radius: 0.5rem;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.close {
  color: #aaa;
  float: right;
  font-size: clamp(1.2rem, 2vw, 1.5rem);
  font-weight: bold;
  cursor: pointer;
}

.close:hover {
  color: black;
}

.form-group {
  margin-bottom: clamp(0.75rem, 1.5vw, 1rem);
}

label {
  display: block;
  margin-bottom: 0.3rem;
  font-weight: bold;
  font-size: clamp(0.75rem, 1vw, 0.85rem);
}

.success-message {
  color: green;
  margin: clamp(0.5rem, 1vw, 0.75rem) 0;
  font-size: clamp(0.75rem, 1vw, 0.85rem);
}

.error-message {
  color: red;
  margin: clamp(0.5rem, 1vw, 0.75rem) 0;
  font-size: clamp(0.75rem, 1vw, 0.85rem);
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

  .btn {
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

  .form-control {
    font-size: clamp(0.7rem, 0.9vw, 0.8rem);
  }

  .modal-content {
    width: clamp(95%, 98vw, 98%);
    margin: clamp(2%, 5vw, 5%) auto;
  }

  .close {
    font-size: clamp(1rem, 1.5vw, 1.2rem);
  }
}
</style>

<!-- Delivery Report Start -->
<div class="container-fluid pt-4 px-4">
  <div class="row bg-light rounded mx-0">
    <div class="col-12">
      <h1 class="heading">Delivery Report</h1>

      <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Delivery information updated successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php elseif (isset($_GET['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Error: <?= htmlspecialchars($_GET['error']) ?>
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
              <th>Qty</th>
              <th>Order Date</th>
              <th>Status</th>
              <th>Est. Delivery</th>
              <th>Actual Delivery</th>
              <th>Notes</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order): ?>
            <tr class="status-<?= htmlspecialchars($order['delivery_status']) ?>">
              <td><?= htmlspecialchars($order['order_id']) ?></td>
              <td><?= htmlspecialchars($order['u_name']) ?></td>
              <td><?= htmlspecialchars($order['p_name']) ?></td>
              <td><?= htmlspecialchars($order['p_qty']) ?></td>
              <td><?= htmlspecialchars($order['date_time']) ?></td>
              <td><?= htmlspecialchars($order['delivery_status']) ?></td>
              <td>
                <?= $order['estimated_delivery_date'] ? htmlspecialchars($order['estimated_delivery_date']) : 'Not set' ?>
              </td>
              <td>
                <?= $order['actual_delivery_date'] ? htmlspecialchars($order['actual_delivery_date']) : 'Not delivered' ?>
              </td>
              <td>
                <?= htmlspecialchars(substr($order['delivery_notes'] ?? '', 0, 30)) ?><?= (strlen($order['delivery_notes'] ?? '') > 30 ? '...' : '') ?>
              </td>
              <td>
                <button onclick="openModal('<?= htmlspecialchars($order['order_id']) ?>')" class="btn"
                  <?php echo ($order['delivery_status'] === 'delivered') ? 'disabled' : ''; ?>>
                  Update
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Delivery Report End -->

<!-- Modal for updating delivery info -->
<div id="deliveryModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">×</span>
    <h2 style="font-size: clamp(1.2rem, 2vw, 1.5rem); margin-bottom: 1rem;">Update Delivery Information</h2>
    <form id="deliveryForm" method="post" action="update_delivery.php">
      <input type="hidden" id="order_id" name="order_id">
      <input type="hidden" id="delivery_id" name="delivery_id">
      <div class="form-group">
        <label for="delivery_status">Status:</label>
        <select id="delivery_status" name="delivery_status" class="form-control" required>
          <option value="pending">Pending</option>
          <option value="processing">Processing</option>
          <option value="shipped">Shipped</option>
          <option value="delivered">Delivered</option>
        </select>
      </div>
      <div class="form-group">
        <label for="estimated_delivery_date">Estimated Delivery Date:</label>
        <input type="date" id="estimated_delivery_date" name="estimated_delivery_date" class="form-control"
          min="<?= date('Y-m-d') ?>">
      </div>
      <div class="form-group">
        <label for="actual_delivery_date">Actual Delivery Date:</label>
        <input type="date" id="actual_delivery_date" name="actual_delivery_date" class="form-control"
          min="<?= date('Y-m-d') ?>">
      </div>
      <div class="form-group">
        <label for="delivery_notes">Notes:</label>
        <textarea id="delivery_notes" name="delivery_notes" class="form-control" rows="3"></textarea>
      </div>
      <div style="margin-top: 1rem;">
        <button type="submit" class="btn">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<?php
include("components/footer.php");
?>

<script>
function openModal(orderId) {
  console.log('Opening modal for order:', orderId);
  fetch('get_delivery_details.php?order_id=' + encodeURIComponent(orderId))
    .then(response => {
      if (!response.ok) {
        return response.text().then(text => {
          throw new Error(`Server responded with ${response.status}: ${text}`);
        });
      }
      return response.json();
    })
    .then(data => {
      console.log('Received data:', data);
      if (data.error) {
        throw new Error(data.error);
      }
      document.getElementById('order_id').value = data.order_id;
      document.getElementById('delivery_id').value = data.delivery_id || '';
      document.getElementById('delivery_status').value = data.delivery_status || 'pending';
      document.getElementById('estimated_delivery_date').value = data.estimated_delivery_date || '';
      document.getElementById('actual_delivery_date').value = data.actual_delivery_date || '';
      document.getElementById('delivery_notes').value = data.delivery_notes || '';
      document.getElementById('deliveryModal').style.display = 'block';
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error loading delivery details: ' + error.message);
    });
}

function closeModal() {
  document.getElementById('deliveryModal').style.display = 'none';
}

window.onclick = function(event) {
  const modal = document.getElementById('deliveryModal');
  if (event.target === modal) {
    modal.style.display = 'none';
  }
};
</script>