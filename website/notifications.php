<?php
include 'components/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Mark notifications as read when page is loaded
if (isset($_GET['mark_as_read']) && $_GET['mark_as_read'] == 'all') {
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

// Get all notifications for the user
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications - The Crafty Corner</title>
  <style>
  .notification-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .notification-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
  }

  .notification-item {
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    background: #f9f9f9;
    border-left: 4px solid #7fad39;
  }

  .notification-item.unread {
    background: #f0f8ff;
    border-left: 4px solid #4a90e2;
  }

  .notification-title {
    font-weight: bold;
    margin-bottom: 5px;
  }

  .notification-time {
    font-size: 12px;
    color: #777;
  }

  .mark-all-read {
    background: #7fad39;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
  }

  .no-notifications {
    text-align: center;
    padding: 30px;
    color: #777;
  }
  </style>
</head>

<body>
  <div class="notification-container">
    <div class="notification-header">
      <h2>Notifications</h2>
      <?php if (count($notifications) > 0): ?>
      <a href="notifications.php?mark_as_read=all" class="mark-all-read">Mark All as Read</a>
      <?php endif; ?>
    </div>

    <?php if (count($notifications) > 0): ?>
    <?php foreach ($notifications as $notification): ?>
    <div class="notification-item <?php echo $notification['is_read'] ? '' : 'unread'; ?>">
      <div class="notification-title"><?php echo htmlspecialchars($notification['title']); ?></div>
      <div class="notification-message"><?php echo htmlspecialchars($notification['message']); ?></div>
      <div class="notification-time">
        <?php echo date('M j, Y g:i a', strtotime($notification['created_at'])); ?>
      </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="no-notifications">
      <i class="fa fa-bell-slash" style="font-size: 48px; margin-bottom: 15px;"></i>
      <p>You don't have any notifications yet.</p>
    </div>
    <?php endif; ?>
  </div>
</body>

</html>

<?php include 'components/footer.php'; ?>