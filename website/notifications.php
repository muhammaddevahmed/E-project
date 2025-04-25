<?php
// Suppress warnings/notices to prevent JSON corruption
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ob_start(); // Start output buffering to catch stray output

include 'components/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle notification deletion via AJAX
if (isset($_POST['delete_notification']) && is_numeric($_POST['notification_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM notifications WHERE notification_id = ? AND user_id = ?");
        $stmt->execute([$_POST['notification_id'], $_SESSION['user_id']]);
        ob_end_clean(); // Clear any buffered output
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit();
    } catch (Exception $e) {
        ob_end_clean(); // Clear any buffered output
        header('Content-Type: application/json', true, 500);
        echo json_encode(['success' => false, 'error' => 'Database error']);
        exit();
    }
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

ob_end_flush(); // Flush the output buffer for normal page rendering
?>


<script src="https://cdn.tailwindcss.com"></script>

<style>
.notification-item {
  transition: all 0.3s ease;
}

.notification-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.fade-out {
  animation: fadeOut 0.5s ease forwards;
}

@keyframes fadeOut {
  from {
    opacity: 1;
    height: auto;
  }

  to {
    opacity: 0;
    height: 0;
    margin-bottom: 0;
    padding: 0;
  }
}
</style>


<body class="bg-gray-100 font-sans">
  <div class="min-h-screen flex justify-center items-start py-12">
    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl p-8">
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Notifications</h2>
        <?php if (count($notifications) > 0): ?>
        <a href="notifications.php?mark_as_read=all"
          class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-200">
          Mark All as Read
        </a>
        <?php endif; ?>
      </div>

      <?php if (count($notifications) > 0): ?>
      <div id="notifications-list">
        <?php foreach ($notifications as $notification): ?>
        <div
          class="notification-item flex justify-between items-start p-6 mb-4 rounded-lg <?php echo $notification['is_read'] ? 'bg-gray-50' : 'bg-blue-50 border-l-4 border-blue-500'; ?>"
          data-notification-id="<?php echo $notification['notification_id']; ?>">
          <div>
            <div class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($notification['title']); ?>
            </div>
            <div class="text-gray-600 mt-1"><?php echo htmlspecialchars($notification['message']); ?></div>
            <div class="text-sm text-gray-500 mt-2">
              <?php echo date('M j, Y g:i a', strtotime($notification['created_at'])); ?>
            </div>
          </div>
          <button class="delete-notification text-red-500 hover:text-red-700"
            data-notification-id="<?php echo $notification['notification_id']; ?>">
            <i class="fas fa-trash-alt"></i>
          </button>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="text-center py-12">
        <i class="fas fa-bell-slash text-gray-400 text-6xl mb-4"></i>
        <p class="text-gray-500 text-lg">You don't have any notifications yet.</p>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete-notification');
    deleteButtons.forEach(button => {
      button.addEventListener('click', async () => {
        const notificationId = button.getAttribute('data-notification-id');
        const notificationItem = button.closest('.notification-item');

        // Show confirmation dialog
        if (!confirm('Are you sure you want to delete this notification?')) {
          return; // Exit if user cancels
        }

        try {
          const response = await fetch('notifications.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `delete_notification=true&notification_id=${notificationId}`
          });

          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }

          const result = await response.json();

          if (result.success) {
            notificationItem.classList.add('fade-out');
            setTimeout(() => {
              notificationItem.remove();

              // Check if no notifications remain
              if (!document.querySelector('.notification-item')) {
                const list = document.getElementById('notifications-list');
                list.innerHTML = `
                                        <div class="text-center py-12">
                                            <i class="fas fa-bell-slash text-gray-400 text-6xl mb-4"></i>
                                            <p class="text-gray-500 text-lg">You don't have any notifications yet.</p>
                                        </div>
                                    `;
              }
            }, 500);
          } else {
            alert('Failed to delete notification: ' + (result.error || 'Unknown error'));
          }
        } catch (error) {
          console.error('Error deleting notification:', error);
          alert('An error occurred while deleting the notification: ' + error.message);
        }
      });
    });
  });
  </script>

  <?php include 'components/footer.php'; ?>