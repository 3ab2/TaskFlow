<?php
require_once __DIR__ . '/../config/database.php';

class NotificationController {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    // Récupérer toutes les notifications d'un utilisateur
    public function getUserNotifications($userId) {
        $query = "SELECT * FROM notifications 
                 WHERE user_id = :user_id 
                 ORDER BY created_at DESC";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Marquer les notifications comme lues
    public function markNotificationsAsRead($userId) {
        $query = "UPDATE notifications 
                 SET is_read = 1 
                 WHERE user_id = :user_id AND is_read = 0";
        
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(['user_id' => $userId]);
    }

    // Récupérer le nombre de notifications non lues
    public function getUnreadCount($userId) {
        $query = "SELECT COUNT(*) as count 
                 FROM notifications 
                 WHERE user_id = :user_id AND is_read = 0";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    // Créer une nouvelle notification
    public function createNotification($userId, $title, $message, $type = 'info', $priority = 'normal') {
        $query = "INSERT INTO notifications (user_id, title, message, type, priority, created_at) 
                 VALUES (:user_id, :title, :message, :type, :priority, NOW())";
        
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'priority' => $priority
        ]);
    }

    // Supprimer une notification
    public function deleteNotification($notificationId, $userId) {
        $query = "DELETE FROM notifications 
                 WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            'id' => $notificationId,
            'user_id' => $userId
        ]);
    }

    // Récupérer les statistiques des notifications
    public function getNotificationStatistics($userId) {
        // Statistiques par type
        $typeQuery = "SELECT 
            type,
            COUNT(*) as count
        FROM notifications
        WHERE user_id = :user_id
        GROUP BY type";

        $typeStmt = $this->pdo->prepare($typeQuery);
        $typeStmt->execute(['user_id' => $userId]);
        $typeStats = $typeStmt->fetchAll(PDO::FETCH_ASSOC);

        // Statistiques par priorité
        $priorityQuery = "SELECT 
            priority,
            COUNT(*) as count
        FROM notifications
        WHERE user_id = :user_id
        GROUP BY priority";

        $priorityStmt = $this->pdo->prepare($priorityQuery);
        $priorityStmt->execute(['user_id' => $userId]);
        $priorityStats = $priorityStmt->fetchAll(PDO::FETCH_ASSOC);

        // Statistiques par mois
        $monthlyQuery = "SELECT 
            MONTH(created_at) as month,
            COUNT(*) as count
        FROM notifications
        WHERE user_id = :user_id
        AND YEAR(created_at) = YEAR(CURRENT_DATE)
        GROUP BY MONTH(created_at)
        ORDER BY month";

        $monthlyStmt = $this->pdo->prepare($monthlyQuery);
        $monthlyStmt->execute(['user_id' => $userId]);
        $monthlyStats = $monthlyStmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'type' => $typeStats,
            'priority' => $priorityStats,
            'monthly' => $monthlyStats
        ];
    }

    // Filtrer les notifications
    public function filterNotifications($userId, $type = null, $priority = null) {
        $query = "SELECT * FROM notifications WHERE user_id = :user_id";
        $params = ['user_id' => $userId];

        if ($type) {
            $query .= " AND type = :type";
            $params['type'] = $type;
        }

        if ($priority) {
            $query .= " AND priority = :priority";
            $params['priority'] = $priority;
        }

        $query .= " ORDER BY created_at DESC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 