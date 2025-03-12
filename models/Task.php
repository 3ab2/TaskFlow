<?php
class Task {
    private $conn;
    private $table = "tasks";

    public $id;
    public $title;
    public $description;
    public $user_id;
    public $status;
    public $priority;
    public $deadline;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // Vérifier le nombre de tâches en cours
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                 WHERE user_id = ? AND status = 'in_progress'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] >= 3 && $this->status == 'in_progress') {
            return ['success' => false, 'message' => 'Vous ne pouvez pas avoir plus de 3 tâches en cours'];
        }

        $query = "INSERT INTO " . $this->table . "
                (title, description, user_id, status, priority, deadline)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->title,
            $this->description,
            $this->user_id,
            $this->status,
            $this->priority,
            $this->deadline
        ]);

        if($stmt->rowCount() > 0) {
            $this->id = $this->conn->lastInsertId();
            return ['success' => true, 'id' => $this->id];
        }
        return ['success' => false, 'message' => 'Erreur lors de la création de la tâche'];
    }

    public function update() {
        // Vérifier le nombre de tâches en cours si on change le statut à 'in_progress'
        if ($this->status == 'in_progress') {
            $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                     WHERE user_id = ? AND status = 'in_progress' AND id != ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$this->user_id, $this->id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['count'] >= 3) {
                return ['success' => false, 'message' => 'Vous ne pouvez pas avoir plus de 3 tâches en cours'];
            }
        }

        $query = "UPDATE " . $this->table . "
                SET title = ?, description = ?, status = ?, 
                    priority = ?, deadline = ?
                WHERE id = ? AND user_id = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $this->title,
            $this->description,
            $this->status,
            $this->priority,
            $this->deadline,
            $this->id,
            $this->user_id
        ]);
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id, $this->user_id]);
    }

    public function read() {
        $query = "SELECT t.*, 
                    (SELECT COUNT(*) FROM subtasks s WHERE s.task_id = t.id) as subtasks_count,
                    (SELECT COUNT(*) FROM subtasks s WHERE s.task_id = t.id AND s.completed = 1) as completed_subtasks
                 FROM " . $this->table . " t
                 WHERE t.user_id = ?
                 ORDER BY 
                    CASE t.status
                        WHEN 'in_progress' THEN 1
                        WHEN 'to_do' THEN 2
                        WHEN 'completed' THEN 3
                    END,
                    CASE t.priority
                        WHEN 'high' THEN 1
                        WHEN 'medium' THEN 2
                        WHEN 'low' THEN 3
                    END,
                    t.deadline ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id, $this->user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTasksStats() {
        $query = "SELECT 
                    COUNT(*) as total_tasks,
                    SUM(CASE WHEN status = 'to_do' THEN 1 ELSE 0 END) as todo_count,
                    SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_count,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count
                 FROM " . $this->table . "
                 WHERE user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
