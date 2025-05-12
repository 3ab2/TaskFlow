<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/Task.php';

class AdminController {
    private $db;
    private $auth;

    public function __construct() {
        global $db;
        $this->db = $db;
        $this->auth = new AuthController();
    }

    public function isAdmin() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        return $user && $user['role'] === 'admin';
    }

    public function getAllUsers() {
        if (!$this->isAdmin()) {
            return ['error' => 'Unauthorized access'];
        }
        $stmt = $this->db->query("SELECT id, username, email, role, created_at FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTasks() {
        if (!$this->isAdmin()) {
            return ['error' => 'Unauthorized access'];
        }
        $stmt = $this->db->query("SELECT tasks.*, users.username FROM tasks JOIN users ON tasks.user_id = users.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMessages() {
        if (!$this->isAdmin()) {
            return ['error' => 'Unauthorized access'];
        }
        $stmt = $this->db->query("SELECT messages.*, users.username FROM messages JOIN users ON messages.user_id = users.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllNotifications() {
        if (!$this->isAdmin()) {
            return ['error' => 'Unauthorized access'];
        }
        $stmt = $this->db->query("SELECT notifications.*, users.username FROM notifications JOIN users ON notifications.user_id = users.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserRole($userId, $newRole) {
        if (!$this->isAdmin()) {
            return ['error' => 'Unauthorized access'];
        }
        
        try {
            $stmt = $this->db->prepare("SELECT username FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE id = ?");
            $result = $stmt->execute([$newRole, $userId]);
            
            if ($result) {
                $this->logActivity(
                    'User Role Update',
                    "Updated role for user {$user['username']} to {$newRole}"
                );
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error updating user role: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($userId) {
        if (!$this->isAdmin()) {
            return ['error' => 'Accès non autorisé'];
        }
        
        try {
            $this->db->beginTransaction();

            // Vérifier si l'utilisateur existe
            $stmt = $this->db->prepare("SELECT id, username FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if (!$user) {
                $this->db->rollBack();
                return ['error' => 'Utilisateur non trouvé'];
            }

            // Supprimer d'abord les données liées
            // 1. Supprimer les tâches de l'utilisateur
            $stmt = $this->db->prepare("DELETE FROM tasks WHERE user_id = ?");
            $stmt->execute([$userId]);

            // 2. Supprimer les messages de l'utilisateur
            $stmt = $this->db->prepare("DELETE FROM messages WHERE user_id = ?");
            $stmt->execute([$userId]);

            // 3. Supprimer les préférences de l'utilisateur
            $stmt = $this->db->prepare("DELETE FROM user_preferences WHERE user_id = ?");
            $stmt->execute([$userId]);

            // 4. Supprimer les activités de l'utilisateur
            $stmt = $this->db->prepare("DELETE FROM admin_activity WHERE admin_id = ?");
            $stmt->execute([$userId]);

            // 5. Supprimer les notifications de l'utilisateur
            $stmt = $this->db->prepare("DELETE FROM notifications WHERE user_id = ?");
            $stmt->execute([$userId]);

            // Enfin, supprimer l'utilisateur
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $result = $stmt->execute([$userId]);
            
            if ($result) {
                $this->logActivity(
                    'User Delete',
                    "Suppression de l'utilisateur {$user['username']}"
                );
                $this->db->commit();
                return true;
            } else {
                $this->db->rollBack();
                return ['error' => 'Échec de la suppression de l\'utilisateur'];
            }
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erreur lors de la suppression de l'utilisateur: " . $e->getMessage());
            return ['error' => 'Erreur de base de données lors de la suppression'];
        }
    }

    public function deleteTask($taskId) {
        if (!$this->isAdmin()) {
            return false;
        }

        try {
            $this->db->beginTransaction();

            // Get task details before deletion
            $stmt = $this->db->prepare("SELECT title FROM tasks WHERE id = ?");
            $stmt->execute([$taskId]);
            $task = $stmt->fetch();
            
            // Check if task exists
            if (!$task) {
                $this->db->rollBack();
                error_log("Error deleting task: Task with ID $taskId not found");
                return false;
            }

            // Check if subtasks table exists
            try {
                $stmt = $this->db->prepare("SHOW TABLES LIKE 'subtasks'");
                $stmt->execute();
                $subtasksTableExists = $stmt->rowCount() > 0;
                
                // Only try to delete subtasks if the table exists
                if ($subtasksTableExists) {
                    $stmt = $this->db->prepare("DELETE FROM subtasks WHERE task_id = ?");
                    $stmt->execute([$taskId]);
                }
            } catch (Exception $e) {
                // If there's an error checking for the subtasks table, log it but continue
                error_log("Error checking for subtasks table: " . $e->getMessage());
            }

            // Delete main task
            $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
            $result = $stmt->execute([$taskId]);

            if ($result) {
                $this->logActivity(
                    'Task Delete',
                    "Deleted task: {$task['title']}"
                );
            }

            $this->db->commit();
            return $result;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error deleting task: " . $e->getMessage());
            return false;
        }
    }

    public function deleteMessage($messageId) {
        if (!$this->isAdmin()) {
            return ['error' => 'Unauthorized access'];
        }
        $stmt = $this->db->prepare("DELETE FROM messages WHERE id = ?");
        return $stmt->execute([$messageId]);
    }

    public function getAdminInfo() {
        if (!$this->isAdmin()) {
            return false;
        }

        $stmt = $this->db->prepare("SELECT id, username, email, role, status, created_at FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAdminActivity() {
        $sql = "SELECT * FROM admin_activity WHERE admin_id = ? ORDER BY created_at DESC LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdminPreferences() {
        $sql = "SELECT * FROM user_preferences WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        $preferences = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si aucune préférence n'existe, retourner les valeurs par défaut
        if (!$preferences) {
            return [
                'language' => 'fr',
                'theme' => 'light',
                'notifications_enabled' => true
            ];
        }
        
        // Convertir notifications_enabled en notifications pour la compatibilité
        $preferences['notifications'] = (bool)$preferences['notifications_enabled'];
        
        return $preferences;
    }

    /**
     * Log an admin activity
     */
    public function logActivity($action, $details) {
        if (!$this->isAdmin()) {
            return false;
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO admin_activity (admin_id, action, details) 
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([$_SESSION['user_id'], $action, $details]);
        } catch (PDOException $e) {
            error_log("Error logging activity: " . $e->getMessage());
            return false;
        }
    }

    public function updateAdminProfile($data) {
        if (!$this->isAdmin()) {
            return ['error' => 'Unauthorized access'];
        }

        try {
            $this->db->beginTransaction();

            // Update username and email
            $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$data['username'], $data['email'], $_SESSION['user_id']]);

            // Update password if provided
            if (!empty($data['new_password'])) {
                if ($data['new_password'] !== $data['confirm_password']) {
                    throw new Exception('Passwords do not match');
                }
                $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashedPassword, $_SESSION['user_id']]);
            }

            $this->db->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function getAdminSettings() {
        if (!$this->isAdmin()) {
            return false;
        }

        // Get settings from user_preferences table
        $stmt = $this->db->prepare("
            SELECT theme, notifications_enabled, language 
            FROM user_preferences 
            WHERE user_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no settings exist, create default settings
        if (!$settings) {
            $settings = [
                'theme' => 'light',
                'notifications_enabled' => true,
                'language' => 'fr'
            ];
            
            $stmt = $this->db->prepare("
                INSERT INTO user_preferences (user_id, theme, notifications_enabled, language) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $_SESSION['user_id'],
                $settings['theme'],
                $settings['notifications_enabled'],
                $settings['language']
            ]);
        }

        return $settings;
    }

    public function updateSettings($type, $data) {
        if (!$this->isAdmin()) {
            return ['error' => 'Unauthorized access'];
        }

        try {
            switch ($type) {
                case 'language':
                    if (!in_array($data['language'], ['fr', 'en', 'ar'])) {
                        throw new Exception('Invalid language');
                    }
                    $stmt = $this->db->prepare("
                        UPDATE user_preferences 
                        SET language = ? 
                        WHERE user_id = ?
                    ");
                    $stmt->execute([$data['language'], $_SESSION['user_id']]);
                    break;

                case 'theme':
                    if (!in_array($data['theme'], ['light', 'dark'])) {
                        throw new Exception('Invalid theme');
                    }
                    $stmt = $this->db->prepare("
                        UPDATE user_preferences 
                        SET theme = ?, notifications_enabled = ? 
                        WHERE user_id = ?
                    ");
                    $stmt->execute([
                        $data['theme'],
                        $data['notifications_enabled'] ? 1 : 0,
                        $_SESSION['user_id']
                    ]);
                    break;

                default:
                    throw new Exception('Invalid settings type');
            }

            return ['success' => true];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Delete a specific activity from the admin's history
     */
    public function deleteActivity($activityId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM admin_activity WHERE id = ? AND admin_id = ?");
            $stmt->execute([$activityId, $_SESSION['user_id']]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting activity: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Clear all activities from the admin's history
     */
    public function clearAllActivities() {
        try {
            $stmt = $this->db->prepare("DELETE FROM admin_activity WHERE admin_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            return true;
        } catch (PDOException $e) {
            error_log("Error clearing activities: " . $e->getMessage());
            return false;
        }
    }

    

    public function updateAdminPreferences($data) {
        if (!$this->isAdmin()) {
            return ['error' => 'Unauthorized access'];
        }

        try {
            $this->db->beginTransaction();

            // Update language
            if (!empty($data['language']) && in_array($data['language'], ['fr', 'en', 'ar'])) {
                $stmt = $this->db->prepare("UPDATE user_preferences SET language = ? WHERE user_id = ?");
                $stmt->execute([$data['language'], $_SESSION['user_id']]);
            }

            // Update theme
            if (!empty($data['theme']) && in_array($data['theme'], ['light', 'dark'])) {
                $stmt = $this->db->prepare("UPDATE user_preferences SET theme = ? WHERE user_id = ?");
                $stmt->execute([$data['theme'], $_SESSION['user_id']]);
            }

            // Update notifications
            if (!empty($data['notifications'])) {
                $stmt = $this->db->prepare("UPDATE user_preferences SET notifications_enabled = ? WHERE user_id = ?");
                $stmt->execute([$data['notifications'] ? 1 : 0, $_SESSION['user_id']]);
            }

            $this->db->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function getUserStatistics() {
        try {
            $sql = "SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as new_users,
                    COUNT(CASE WHEN last_login >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as active_users
                    FROM users";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Calculer le taux de croissance
            $sql_previous = "SELECT COUNT(*) as previous_total 
                           FROM users 
                           WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $stmt_previous = $this->db->prepare($sql_previous);
            $stmt_previous->execute();
            $previous = $stmt_previous->fetch(PDO::FETCH_ASSOC);

            $growth = $previous['previous_total'] > 0 
                ? round(($result['new_users'] / $previous['previous_total']) * 100, 1)
                : 0;

            return [
                'total' => $result['total'],
                'new_users' => $result['new_users'],
                'active_users' => $result['active_users'],
                'growth' => $growth
            ];
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des statistiques utilisateurs: " . $e->getMessage());
            return [
                'total' => 0,
                'new_users' => 0,
                'active_users' => 0,
                'growth' => 0
            ];
        }
    }

    public function getTaskStatistics() {
        try {
            $sql = "SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
                    COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as in_progress,
                    COUNT(CASE WHEN status = 'to_do' THEN 1 END) as to_do,
                    COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as new_tasks
                    FROM tasks";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Calculer le taux de croissance
            $sql_previous = "SELECT COUNT(*) as previous_total 
                           FROM tasks 
                           WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $stmt_previous = $this->db->prepare($sql_previous);
            $stmt_previous->execute();
            $previous = $stmt_previous->fetch(PDO::FETCH_ASSOC);

            $growth = $previous['previous_total'] > 0 
                ? round(($result['new_tasks'] / $previous['previous_total']) * 100, 1)
                : 0;

            return [
                'total' => $result['total'],
                'completed' => $result['completed'],
                'in_progress' => $result['in_progress'],
                'to_do' => $result['to_do'],
                'new_tasks' => $result['new_tasks'],
                'growth' => $growth
            ];
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des statistiques des tâches: " . $e->getMessage());
            return [
                'total' => 0,
                'completed' => 0,
                'in_progress' => 0,
                'to_do' => 0,
                'new_tasks' => 0,
                'growth' => 0
            ];
        }
    }

    public function getMessageStatistics() {
        try {
            $sql = "SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as new_messages,
                    COUNT(DISTINCT user_id) as unique_senders
                    FROM messages";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Calculer le taux de croissance
            $sql_previous = "SELECT COUNT(*) as previous_total 
                           FROM messages 
                           WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $stmt_previous = $this->db->prepare($sql_previous);
            $stmt_previous->execute();
            $previous = $stmt_previous->fetch(PDO::FETCH_ASSOC);

            $growth = $previous['previous_total'] > 0 
                ? round(($result['new_messages'] / $previous['previous_total']) * 100, 1)
                : 0;

            return [
                'total' => $result['total'],
                'new_messages' => $result['new_messages'],
                'unique_senders' => $result['unique_senders'],
                'growth' => $growth
            ];
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des statistiques des messages: " . $e->getMessage());
            return [
                'total' => 0,
                'new_messages' => 0,
                'unique_senders' => 0,
                'growth' => 0
            ];
        }
    }

    public function addUser($username, $email, $password, $role) {
        if (!$this->isAdmin()) {
            throw new Exception('Accès non autorisé');
        }

        try {
            // Vérifier si l'email existe déjà
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                throw new Exception("Un utilisateur avec cet email existe déjà");
            }

            // Vérifier si le nom d'utilisateur existe déjà
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                throw new Exception("Ce nom d'utilisateur est déjà pris");
            }

            // Hasher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insérer le nouvel utilisateur
            $stmt = $this->db->prepare("
                INSERT INTO users (username, email, password, role, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            
            $result = $stmt->execute([$username, $email, $hashedPassword, $role]);

            if ($result) {
                $this->logActivity(
                    'User Creation',
                    "Created new user: {$username}"
                );
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error adding user: " . $e->getMessage());
            throw $e;
        }
    }

    public function addTask($title, $description, $assigned_to, $priority, $due_date, $status) {
        if (!$this->isAdmin()) {
            throw new Exception('Accès non autorisé');
        }

        try {
            // Vérifier si l'utilisateur assigné existe
            $stmt = $this->db->prepare("SELECT id FROM users WHERE id = ?");
            $stmt->execute([$assigned_to]);
            if (!$stmt->fetch()) {
                throw new Exception("L'utilisateur assigné n'existe pas");
            }

            // Insérer la nouvelle tâche
            $stmt = $this->db->prepare("
                INSERT INTO tasks (title, description, user_id, priority, deadline, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $result = $stmt->execute([$title, $description, $assigned_to, $priority, $due_date, $status]);

            if ($result) {
                $this->logActivity(
                    'Task Creation',
                    "Created new task: {$title}"
                );
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error adding task: " . $e->getMessage());
            throw $e;
        }
    }

    public function sendNotification($title, $message, $recipients, $type, $priority) {
        if (!$this->isAdmin()) {
            throw new Exception('Accès non autorisé');
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO notifications 
                (user_id, title, message, type, priority, is_read, created_at) 
                VALUES (?, ?, ?, ?, ?, 0, NOW())
            ");
            
            foreach ($recipients as $userId) {
                $stmt->execute([$userId, $title, $message, $type, $priority]);
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'envoi de la notification : " . $e->getMessage());
            return false;
        }
    }

    public function getNbNotif() {
        $notifications = $this->getUserNotifications();
        return count($notifications);
    }

    public function getUserNotifications() {
        if (!isset($_SESSION['user_id'])) {
            return ['error' => 'User not logged in'];
        }

        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUnreadNotificationsCount($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM notifications 
                WHERE user_id = ? AND is_read = 0
            ");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des notifications : " . $e->getMessage());
            return 0;
        }
    }

    public function markNotificationsAsRead($userId) {
        try {
            $stmt = $this->db->prepare("
                UPDATE notifications 
                SET is_read = 1 
                WHERE user_id = ? AND is_read = 0
            ");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour des notifications : " . $e->getMessage());
            return false;
        }
    }

    public function getNotificationStatistics() {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN type = 'info' THEN 1 ELSE 0 END) as info_count,
                    SUM(CASE WHEN type = 'warning' THEN 1 ELSE 0 END) as warning_count,
                    SUM(CASE WHEN type = 'error' THEN 1 ELSE 0 END) as error_count,
                    SUM(CASE WHEN type = 'success' THEN 1 ELSE 0 END) as success_count
                  FROM notifications";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ajouter des conseils basés sur les statistiques
        $advice = [];
        
        if ($stats['error_count'] > 0) {
            $advice[] = "Il y a des notifications d'erreur à traiter. Vérifiez les problèmes signalés.";
        }
        
        if ($stats['warning_count'] > 5) {
            $advice[] = "Nombre élevé d'avertissements. Considérez une revue des processus.";
        }
        
        if ($stats['success_count'] < $stats['total'] * 0.3) {
            $advice[] = "Le taux de succès est faible. Analysez les causes des problèmes.";
        }

        return [
            'stats' => $stats,
            'advice' => $advice
        ];
    }

    /**
     * Delete a notification
     * 
     * @param int $notificationId The ID of the notification to delete
     * @return bool True if the notification was deleted successfully, false otherwise
     */
    public function deleteNotification($notificationId) {
        if (!$this->isAdmin()) {
            return ['error' => 'Unauthorized access'];
        }
        
        try {
            $stmt = $this->db->prepare("SELECT id, title FROM notifications WHERE id = ?");
            $stmt->execute([$notificationId]);
            $notification = $stmt->fetch();
            
            if (!$notification) {
                return ['error' => 'Notification not found'];
            }
            
            $stmt = $this->db->prepare("DELETE FROM notifications WHERE id = ?");
            $result = $stmt->execute([$notificationId]);
            
            if ($result) {
                $this->logActivity(
                    'Notification Delete',
                    "Deleted notification: {$notification['title']}"
                );
                return ['success' => true];
            } else {
                return ['error' => 'Failed to delete notification'];
            }
        } catch (PDOException $e) {
            error_log("Error deleting notification: " . $e->getMessage());
            return ['error' => 'Database error while deleting notification'];
        }
    }

    /**
     * Update a notification
     * 
     * @param int $notificationId The ID of the notification to update
     * @param array $data The data to update (title, message, type, priority)
     * @return array Result of the update operation
     */
    public function updateNotification($notificationId, $data) {
        if (!$this->isAdmin()) {
            return ['error' => 'Unauthorized access'];
        }
        
        try {
            $stmt = $this->db->prepare("SELECT id FROM notifications WHERE id = ?");
            $stmt->execute([$notificationId]);
            $notification = $stmt->fetch();
            
            if (!$notification) {
                return ['error' => 'Notification not found'];
            }
            
            $updateFields = [];
            $params = [];
            
            if (isset($data['title'])) {
                $updateFields[] = "title = ?";
                $params[] = $data['title'];
            }
            
            if (isset($data['message'])) {
                $updateFields[] = "message = ?";
                $params[] = $data['message'];
            }
            
            if (isset($data['type']) && in_array($data['type'], ['info', 'success', 'warning', 'error'])) {
                $updateFields[] = "type = ?";
                $params[] = $data['type'];
            }
            
            if (isset($data['priority']) && in_array($data['priority'], ['low', 'normal', 'high'])) {
                $updateFields[] = "priority = ?";
                $params[] = $data['priority'];
            }
            
            if (empty($updateFields)) {
                return ['error' => 'No valid fields to update'];
            }
            
            $params[] = $notificationId;
            
            $sql = "UPDATE notifications SET " . implode(", ", $updateFields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                $this->logActivity(
                    'Notification Update',
                    "Updated notification ID: {$notificationId}"
                );
                return ['success' => true];
            } else {
                return ['error' => 'Failed to update notification'];
            }
        } catch (PDOException $e) {
            error_log("Error updating notification: " . $e->getMessage());
            return ['error' => 'Database error while updating notification'];
        }
    }
}

