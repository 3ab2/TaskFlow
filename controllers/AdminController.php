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
            return ['error' => 'Unauthorized access'];
        }
        
        try {
            $stmt = $this->db->prepare("SELECT username FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $result = $stmt->execute([$userId]);
            
            if ($result) {
                $this->logActivity(
                    'User Delete',
                    "Deleted user {$user['username']}"
                );
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
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

            // Delete subtasks
            $stmt = $this->db->prepare("DELETE FROM subtasks WHERE task_id = ?");
            $stmt->execute([$taskId]);

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
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending,
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
                'pending' => $result['pending'],
                'new_tasks' => $result['new_tasks'],
                'growth' => $growth
            ];
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des statistiques des tâches: " . $e->getMessage());
            return [
                'total' => 0,
                'completed' => 0,
                'in_progress' => 0,
                'pending' => 0,
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
            $this->db->beginTransaction();

            foreach ($recipients as $recipient_id) {
                // Vérifier si le destinataire existe
                $stmt = $this->db->prepare("SELECT id FROM users WHERE id = ?");
                $stmt->execute([$recipient_id]);
                if (!$stmt->fetch()) {
                    throw new Exception("Un ou plusieurs destinataires n'existent pas");
                }

                // Insérer la notification
                $stmt = $this->db->prepare("
                    INSERT INTO notifications (user_id, title, message, type, priority, is_read, created_at)
                    VALUES (?, ?, ?, ?, ?, FALSE, NOW())
                ");
                
                $result = $stmt->execute([$recipient_id, $title, $message, $type, $priority]);
                
                if (!$result) {
                    throw new Exception("Erreur lors de l'envoi de la notification");
                }
            }

            $this->db->commit();
            $this->logActivity(
                'Notification Sent',
                "Sent notification: {$title} to " . count($recipients) . " recipients"
            );

            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error sending notification: " . $e->getMessage());
            throw $e;
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
}