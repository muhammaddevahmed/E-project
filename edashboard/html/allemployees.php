<?php
include("components/header.php");

// Check user type
$user_type = $_SESSION['user_type'] ?? '';

$stmt = $pdo->prepare("
    SELECT u.user_id, u.full_name, e.employee_id, e.role 
    FROM users u 
    JOIN employees e ON u.user_id = e.user_id
    WHERE u.user_type = 'employee'
    ORDER BY u.full_name
");
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
body {
  font-family: Arial, sans-serif;
  margin: 20px;
}

h1 {
  color: #333;
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

.action-buttons {
  display: flex;
  gap: 5px;
}

.btn {
  padding: 5px 10px;
  text-decoration: none;
  border-radius: 3px;
  font-size: 14px;
}

.btn-edit {
  background-color: #4CAF50;
  color: white;
}

.btn-delete {
  background-color: #f44336;
  color: white;
}

.btn[disabled] {
  background-color: #ccc;
  color: #666;
  pointer-events: none;
}
</style>

<h1>Employee List</h1>

<!-- Invoice Management Start -->
<div class="container-fluid pt-4 px-4">
  <?php if ($user_type === 'employee'): ?>
  <div class="alert alert-warning" role="alert">
    You do not have permission to edit or decline data. All actions are disabled.
  </div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>Employee ID</th>
        <th>User ID</th>
        <th>Full Name</th>
        <th>Role</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($employees): ?>
      <?php foreach ($employees as $employee): ?>
      <tr>
        <td><?php echo htmlspecialchars($employee['employee_id']); ?></td>
        <td><?php echo htmlspecialchars($employee['user_id']); ?></td>
        <td><?php echo htmlspecialchars($employee['full_name']); ?></td>
        <td><?php echo htmlspecialchars($employee['role']); ?></td>
        <td class="action-buttons">
          <a href="edit_employee.php?id=<?php echo $employee['employee_id']; ?>" class="btn btn-edit"
            <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>Edit</a>
          <a href="delete_employee.php?id=<?php echo $employee['employee_id']; ?>" class="btn btn-delete"
            onclick="return confirm('Are you sure you want to delete this employee?')"
            <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php else: ?>
      <tr>
        <td colspan="5" style="text-align: center;">No employees found.</td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <?php
include("components/footer.php");
?>