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
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.heading {
  font-size: 2.5rem;
  font-weight: 700;
  color: #7fad39;
  margin-bottom: 1rem;
  text-align: center;
}

th,
td {
  padding: 12px;
  border: 1px solid #ddd;
  text-align: left;
}

th {
  background-color: #f2f2f2;
  position: sticky;
  top: 0;
}

tr:nth-child(even) {
  background-color: #f9f9f9;
}

tr:hover {
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

.form-control {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}

.btn {
  padding: 8px 12px;
  cursor: pointer;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
}

.btn:hover {
  background-color: #45a049;
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
  margin: 10% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 50%;
  border-radius: 5px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover {
  color: black;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.success-message {
  color: green;
  margin: 10px 0;
}

.error-message {
  color: red;
  margin: 10px 0;
}
</style>

<h1 class="heading">Delivery Report</h1>

<?php if (isset($_GET['success'])): ?>
<div class="success-message">Delivery information updated successfully!</div>
<?php elseif (isset($_GET['error'])): ?>
<div class="error-message">Error: <?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<table>
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
      <td><?= $order['estimated_delivery_date'] ? htmlspecialchars($order['estimated_delivery_date']) : 'Not set' ?>
      </td>
      <td><?= $order['actual_delivery_date'] ? htmlspecialchars($order['actual_delivery_date']) : 'Not delivered' ?>
      </td>
      <td>
        <?= htmlspecialchars(substr($order['delivery_notes'] ?? '', 0, 30)) ?><?= (strlen($order['delivery_notes'] ?? '') > 30 ? '...' : '') ?>
      </td>
      <td>
        <button onclick="openModal('<?= htmlspecialchars($order['order_id']) ?>')" class="btn">Update</button>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Modal for updating delivery info -->
<div id="deliveryModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">×</span>
    <h2>Update Delivery Information</h2>
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
      <div style="margin-top: 20px;">
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