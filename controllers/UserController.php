<?php
require_once __DIR__ . '/../includes/config.php';

class UserController {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function logActivity($userId, $actionType, $description, $icon = null, $color = null) {
        try {
            // Définir les icônes et couleurs par défaut selon le type d'action
            if (!$icon || !$color) {
                switch ($actionType) {
                    case 'LOGIN':
                        $icon = 'fa-sign-in-alt';
                        $color = 'success';
                        break;
                    case 'TASK_CREATE':
                        $icon = 'fa-plus';
                        $color = 'primary';
                        break;
                    case 'TASK_COMPLETE':
                        $icon = 'fa-check';
                        $color = 'success';
                        break;
                    case 'TASK_UPDATE':
                        $icon = 'fa-edit';
                        $color = 'info';
                        break;
                    case 'TASK_DELETE':
                        $icon = 'fa-trash';
                        $color = 'danger';
                        break;
                    case 'PROFILE_UPDATE':
                        $icon = 'fa-user-edit';
                        $color = 'warning';
                        break;
                    case 'SETTINGS_UPDATE':
                        $icon = 'fa-cog';
                        $color = 'secondary';
                        break;
                    case 'MESSAGE_SEND':
                        $icon = 'fa-paper-plane';
                        $color = 'info';
                        break;
                    default:
                        $icon = 'fa-circle';
                        $color = 'primary';
                }
            }

            $sql = "INSERT INTO user_activity (user_id, action_type, description, icon, color) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$userId, $actionType, $description, $icon, $color]);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'enregistrement de l'activité : " . $e->getMessage());
            return false;
        }
    }

    public function getUserActivities($userId, $limit = 50) {
        try {
            $sql = "SELECT * FROM user_activity 
                    WHERE user_id = ? 
                    ORDER BY created_at DESC 
                    LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des activités : " . $e->getMessage());
            return [];
        }
    }
} 