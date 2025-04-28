<?php
// Suppress warnings/notices to prevent JSON corruption
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ob_start(); // Start output buffering to catch stray output

include 'components/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to see your notifications.'); window.location.href='login.php';</script>";
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

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
  .notification-item {
    transition: all 0.3s ease;
    position: relative;
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
      border: none;
    }
  }

  .delete-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    color: #ef4444;
    background: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    opacity: 0.7;
  }

  .delete-btn:hover {
    opacity: 1;
    transform: scale(1.1);
  }

  .delete-btn:active {
    transform: scale(0.95);
  }

  .promo-accepted {
    background-color: #d1fae5;
    border-left: 4px solid #10b981;
  }

  .promo-rejected {
    background-color: #fee2e2;
    border-left: 4px solid #ef4444;
  }

  .promo-code {
    font-weight: bold;
    color: #10b981;
  }
  </style>
</head>

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
        <div class="notification-item relative flex justify-between items-start p-6 mb-4 rounded-lg 
                    <?php
                        echo $notification['is_read'] ? 'bg-gray-50' : (
                            $notification['title'] === 'Promo Code Request Approved' ? 'promo-accepted' :
                            ($notification['title'] === 'Promo Code Request Rejected' ? 'promo-rejected' : 'bg-blue-50 border-l-4 border-blue-500')
                        );
                    ?>" data-notification-id="<?php echo $notification['notification_id']; ?>">

          <!-- Delete Button -->
          <button class="delete-btn" data-notification-id="<?php echo $notification['notification_id']; ?>">
            <i class="fas fa-trash-alt"></i>
          </button>

          <div class="pr-8">
            <div class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($notification['title']); ?>
            </div>
            <div class="text-gray-600 mt-1">
              <?php 
                            $message = htmlspecialchars($notification['message']);
                            // Highlight promo code in the message
                            if ($notification['title'] === 'Promo Code Request Approved') {
                                preg_match("/code '([^']+)'/", $message, $matches);
                                if ($matches[1]) {
                                    $message = str_replace($matches[0], "code '<span class='promo-code'>{$matches[1]}</span>'", $message);
                                }
                            }
                            echo $message;
                            ?>
            </div>
            <div class="text-sm text-gray-500 mt-2">
              <?php echo date('M j, Y g:i a', strtotime($notification['created_at'])); ?>
              <?php if ($notification['related_table'] === 'promo_codes' && !$notification['is_read']): ?>

              <?php endif; ?>
            </div>
          </div>
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
    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', async (e) => {
        e.stopPropagation(); // Prevent any parent click handlers

        const notificationId = button.getAttribute('data-notification-id');
        const notificationItem = button.closest('.notification-item');

        // Show confirmation dialog
        if (!confirm('Are you sure you want to delete this notification?')) {
          return; // Exit if user cancels
        }

        try {
          // Show loading state
          button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

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
            // Add fade-out animation
            notificationItem.classList.add('fade-out');

            // Remove after animation completes
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
            button.innerHTML = '<i class="fas fa-trash-alt"></i>';
          }
        } catch (error) {
          console.error('Error deleting notification:', error);
          alert('An error occurred while deleting the notification: ' + error.message);
          button.innerHTML = '<i class="fas fa-trash-alt"></i>';
        }
      });
    });
  });
  </script>

  <?php include 'components/footer.php'; ?>
</body>

</html>