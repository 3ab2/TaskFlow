<?php
require_once __DIR__ . '/../config/database.php';

class MessageController {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    // ... existing code ...

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

    public function markMessagesAsRead($userId) {
        $query = "INSERT INTO message_reads (user_id, last_read) 
                  VALUES (:user_id, NOW()) 
                  ON DUPLICATE KEY UPDATE last_read = NOW()";
        
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(['user_id' => $userId]);
    }
} 