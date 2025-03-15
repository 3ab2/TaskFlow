<?php
class TaskController
{
    private $db;
    private $table = 'tasks';

    public function __construct()
    {
        require_once __DIR__ . "/../config/database.php";
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getTasks()
    {
        try {
            $query = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY 
                CASE status
                    WHEN 'in_progress' THEN 1
                    WHEN 'to_do' THEN 2
                    WHEN 'completed' THEN 3
                END,
                CASE priority
                    WHEN 'high' THEN 1
                    WHEN 'medium' THEN 2
                    WHEN 'low' THEN 3
                END,
                deadline ASC";

            $stmt = $this->db->prepare($query);
            $stmt->execute([$_SESSION['user_id']]);

            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la récupération des tâches'
            ];
        }
    }

    public function getTask($id)
    {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id, $_SESSION['user_id']]);

            $task = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($task) {
                return [
                    'success' => true,
                    'data' => $task
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Tâche non trouvée'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la récupération de la tâche'
            ];
        }
    }

    public function createTask($data)
    {
        try {
            // Vérifier si l'utilisateur a déjà 3 tâches en cours
            if ($data['status'] === 'in_progress') {
                $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = ? AND status = 'in_progress'");
                $stmt->execute([$_SESSION['user_id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result['count'] >= 3) {
                    return [
                        'success' => false,
                        'message' => 'Vous ne pouvez pas avoir plus de 3 tâches en cours'
                    ];
                }
            }

            $query = "INSERT INTO {$this->table} (title, description, priority, status, deadline, user_id) 
                     VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([
                $data['title'],
                $data['description'] ?? '',
                $data['priority'],
                $data['status'],
                $data['deadline'],
                $_SESSION['user_id']
            ]);

            if ($success) {
                return [
                    'success' => true,
                    'message' => 'Tâche créée avec succès',
                    'data' => ['id' => $this->db->lastInsertId()]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création de la tâche'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la création de la tâche'
            ];
        }
    }

    public function updateTask($id, $data)
    {
        try {
            // Vérifier si la tâche appartient à l'utilisateur
            $stmt = $this->db->prepare("SELECT status FROM {$this->table} WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $_SESSION['user_id']]);
            $task = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$task) {
                return [
                    'success' => false,
                    'message' => 'Tâche non trouvée ou non autorisée'
                ];
            }

            // Vérifier la limite de 3 tâches en cours
            if (isset($data['status']) && $data['status'] === 'in_progress' && $task['status'] !== 'in_progress') {
                $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = ? AND status = 'in_progress'");
                $stmt->execute([$_SESSION['user_id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result['count'] >= 3) {
                    return [
                        'success' => false,
                        'message' => 'Vous ne pouvez pas avoir plus de 3 tâches en cours'
                    ];
                }
            }

            // Construire la requête de mise à jour
            $updateFields = [];
            $params = [];

            if (isset($data['title'])) {
                $updateFields[] = 'title = ?';
                $params[] = $data['title'];
            }
            if (isset($data['description'])) {
                $updateFields[] = 'description = ?';
                $params[] = $data['description'];
            }
            if (isset($data['priority'])) {
                $updateFields[] = 'priority = ?';
                $params[] = $data['priority'];
            }
            if (isset($data['status'])) {
                $updateFields[] = 'status = ?';
                $params[] = $data['status'];
            }
            if (isset($data['deadline'])) {
                $updateFields[] = 'deadline = ?';
                $params[] = $data['deadline'];
            }

            if (empty($updateFields)) {
                return [
                    'success' => false,
                    'message' => 'Aucune donnée à mettre à jour'
                ];
            }

            $params[] = $id;
            $params[] = $_SESSION['user_id'];

            $query = "UPDATE {$this->table} SET " . implode(', ', $updateFields) .
                " WHERE id = ? AND user_id = ?";

            $stmt = $this->db->prepare($query);
            $success = $stmt->execute($params);

            return [
                'success' => $success,
                'message' => $success ? 'Tâche mise à jour avec succès' : 'Erreur lors de la mise à jour'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la tâche'
            ];
        }
    }

    public function deleteTask($id)
    {
        try {
            $query = "DELETE FROM {$this->table} WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$id, $_SESSION['user_id']]);

            return [
                'success' => $success,
                'message' => $success ? 'Tâche supprimée avec succès' : 'Erreur lors de la suppression'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression de la tâche'
            ];
        }
    }

    public function getTaskStats()
    {
        try {
            $stats = [
                'total' => 0,
                'to_do' => 0,
                'in_progress' => 0,
                'completed' => 0,
                'overdue' => 0
            ];

            // Total des tâches
            $stmt = $this->db->prepare("SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'to_do' THEN 1 ELSE 0 END) as to_do,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN deadline < CURRENT_DATE AND status != 'completed' THEN 1 ELSE 0 END) as overdue
                FROM {$this->table} 
                WHERE user_id = ?");

            $stmt->execute([$_SESSION['user_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
            ];
        }
    }
}
