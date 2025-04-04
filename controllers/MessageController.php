<?php
require_once __DIR__ . '/../config/database.php';

class MessageController {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    // ... existing code ...

    // Récupérer les statistiques des messages par mois
    public function getMessageStatistics($userId) {
        // Récupérer les messages par mois
        $query = "SELECT 
            MONTH(created_at) as month,
            COUNT(*) as total,
            SUM(CASE WHEN user_id = :user_id THEN 1 ELSE 0 END) as sent,
            SUM(CASE WHEN user_id != :user_id THEN 1 ELSE 0 END) as received
        FROM messages 
        WHERE YEAR(created_at) = YEAR(CURRENT_DATE)
        GROUP BY MONTH(created_at)
        ORDER BY month";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        $monthlyStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer le nombre total de messages
        $totalQuery = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN user_id = :user_id THEN 1 ELSE 0 END) as total_sent,
            SUM(CASE WHEN user_id != :user_id THEN 1 ELSE 0 END) as total_received
        FROM messages";

        $totalStmt = $this->pdo->prepare($totalQuery);
        $totalStmt->execute(['user_id' => $userId]);
        $totalStats = $totalStmt->fetch(PDO::FETCH_ASSOC);

        return [
            'monthly' => $monthlyStats,
            'total' => $totalStats
        ];
    }

    // Récupérer le nombre de messages non lus
    public function getUnreadMessagesCount($userId) {
        $query = "SELECT COUNT(*) as count 
                 FROM messages 
                 WHERE user_id != :user_id 
                 AND created_at > (
                     SELECT COALESCE(MAX(last_read), '1970-01-01') 
                     FROM message_reads 
                     WHERE user_id = :user_id
                 )";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function hasUnreadMessages($userId) {
        return $this->getUnreadMessagesCount($userId) > 0;
    }

    // Marquer les messages comme lus
    public function markMessagesAsRead($userId) {
        $query = "INSERT INTO message_reads (user_id, last_read) 
                 VALUES (:user_id, NOW()) 
                 ON DUPLICATE KEY UPDATE last_read = NOW()";
        
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(['user_id' => $userId]);
    }
} 