<?php
include 'php/db_connection.php';

class Notifications {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getUnreadCount($user_id) {
        $count = 0;
        
        // Orders
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM orders WHERE u_id = ? AND read_status = 0");
        $stmt->execute([$user_id]);
        $count += $stmt->fetch()['count'];
        
        // Payments
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM payments p 
                                   JOIN orders o ON p.payment_id = o.payment_id 
                                   WHERE o.u_id = ? AND p.read_status = 0");
        $stmt->execute([$user_id]);
        $count += $stmt->fetch()['count'];
        
        // Returns
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM returns r 
                                   JOIN orders o ON r.order_id = o.order_id 
                                   WHERE o.u_id = ? AND r.read_status = 0");
        $stmt->execute([$user_id]);
        $count += $stmt->fetch()['count'];
        
        // Deliveries
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM deliveries 
                                   WHERE user_id = ? AND read_status = 0");
        $stmt->execute([$user_id]);
        $count += $stmt->fetch()['count'];
        
        return $count;
    }
    
    public function getRecentNotifications($user_id, $limit = 5) {
        $notifications = [];
        
        // Orders
        $stmt = $this->pdo->prepare("SELECT o.order_id, o.status, o.delivery_status, o.date_time 
                                   FROM orders o 
                                   WHERE o.u_id = ? 
                                   ORDER BY o.date_time DESC LIMIT ?");
        $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $notifications[] = [
                'type' => 'order',
                'id' => $row['order_id'],
                'message' => $this->getOrderMessage($row['status'], $row['delivery_status']),
                'time' => $row['date_time'],
                'read' => false
            ];
        }
        
        // Payments
        $stmt = $this->pdo->prepare("SELECT p.payment_id, p.payment_status, p.payment_date 
                                    FROM payments p 
                                    JOIN orders o ON p.payment_id = o.payment_id 
                                    WHERE o.u_id = ? 
                                    ORDER BY p.payment_date DESC LIMIT ?");
        $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $notifications[] = [
                'type' => 'payment',
                'id' => $row['payment_id'],
                'message' => $this->getPaymentMessage($row['payment_status']),
                'time' => $row['payment_date'],
                'read' => false
            ];
        }
        
        // Sort by time
        usort($notifications, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        return array_slice($notifications, 0, $limit);
    }
    
    private function getOrderMessage($status, $delivery_status) {
        if ($delivery_status != 'pending') {
            return "Your order is now " . ucfirst($delivery_status);
        }
        return "Your order status changed to " . ucfirst($status);
    }
    
    private function getPaymentMessage($status) {
        return "Payment " . ucfirst($status);
    }
    
    public function markAsRead($user_id, $type, $id) {
        switch ($type) {
            case 'order':
                $stmt = $this->pdo->prepare("UPDATE orders SET read_status = 1 WHERE order_id = ? AND u_id = ?");
                break;
            case 'payment':
                $stmt = $this->pdo->prepare("UPDATE payments SET read_status = 1 WHERE payment_id = ?");
                break;
            case 'return':
                $stmt = $this->pdo->prepare("UPDATE returns SET read_status = 1 WHERE return_id = ?");
                break;
            case 'delivery':
                $stmt = $this->pdo->prepare("UPDATE deliveries SET read_status = 1 WHERE delivery_id = ? AND user_id = ?");
                break;
            default:
                return false;
        }
        
        if ($type == 'delivery' || $type == 'order') {
            return $stmt->execute([$id, $user_id]);
        }
        return $stmt->execute([$id]);
    }
}

// Initialize notifications
$notifications = new Notifications($pdo);
?>