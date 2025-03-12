<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /pfe/views/auth/login.php');
    exit();
}

require_once "../controllers/TaskController.php";
$taskController = new TaskController();
$tasksResult = $taskController->getTasks();
$statsResult = $taskController->getTaskStats();

$tasks = $tasksResult['success'] ? $tasksResult['data'] : [];
$stats = $statsResult['success'] ? $statsResult['data'] : [];
?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - TaskManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/dashboard.css">
</head>
<body>
    <?php include "../includes/navbar.php"; ?>

    <div class="container-fluid py-4">
        <!-- En-tête avec statistiques -->
        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-tasks fa-2x mb-2 text-primary"></i>
                <h3><?php echo $stats['total'] ?? 0; ?></h3>
                <p>Tâches totales</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-spinner fa-2x mb-2 text-warning"></i>
                <h3><?php echo $stats['in_progress'] ?? 0; ?></h3>
                <p>En cours</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                <h3><?php echo $stats['completed'] ?? 0; ?></h3>
                <p>Terminées</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock fa-2x mb-2 text-danger"></i>
                <h3><?php echo $stats['overdue'] ?? 0; ?></h3>
                <p>En retard</p>
            </div>
        </div>

        <!-- Bouton Nouvelle tâche -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Mes tâches</h2>
            <button id="addTaskBtn" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle tâche
            </button>
        </div>

        <!-- Tableau Kanban -->
        <div class="task-board">
            <div class="task-column" data-status="to_do" id="todoTasks">
                <h5><i class="fas fa-list"></i> À faire</h5>
                <?php foreach ($tasks as $task): ?>
                    <?php if ($task['status'] === 'to_do'): ?>
                        <div class="task-card priority-<?php echo $task['priority']; ?>" 
                             data-task-id="<?php echo $task['id']; ?>">
                            <h6><?php echo htmlspecialchars($task['title']); ?></h6>
                            <p class="mb-2"><?php echo htmlspecialchars($task['description']); ?></p>
                            <div class="deadline">
                                <i class="far fa-clock"></i> 
                                <?php echo $task['deadline'] ? date('d/m/Y', strtotime($task['deadline'])) : 'Pas de date limite'; ?>
                            </div>
                            <div class="task-actions">
                                <button class="btn btn-sm btn-outline-primary edit-task" 
                                        data-task-id="<?php echo $task['id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-task" 
                                        data-task-id="<?php echo $task['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <div class="task-column" data-status="in_progress" id="inProgressTasks">
                <h5><i class="fas fa-spinner"></i> En cours</h5>
                <?php foreach ($tasks as $task): ?>
                    <?php if ($task['status'] === 'in_progress'): ?>
                        <div class="task-card priority-<?php echo $task['priority']; ?>" 
                             data-task-id="<?php echo $task['id']; ?>">
                            <h6><?php echo htmlspecialchars($task['title']); ?></h6>
                            <p class="mb-2"><?php echo htmlspecialchars($task['description']); ?></p>
                            <div class="deadline">
                                <i class="far fa-clock"></i> 
                                <?php echo $task['deadline'] ? date('d/m/Y', strtotime($task['deadline'])) : 'Pas de date limite'; ?>
                            </div>
                            <div class="task-actions">
                                <button class="btn btn-sm btn-outline-primary edit-task" 
                                        data-task-id="<?php echo $task['id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-task" 
                                        data-task-id="<?php echo $task['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <div class="task-column" data-status="completed" id="completedTasks">
                <h5><i class="fas fa-check"></i> Terminées</h5>
                <?php foreach ($tasks as $task): ?>
                    <?php if ($task['status'] === 'completed'): ?>
                        <div class="task-card priority-<?php echo $task['priority']; ?>" 
                             data-task-id="<?php echo $task['id']; ?>">
                            <h6><?php echo htmlspecialchars($task['title']); ?></h6>
                            <p class="mb-2"><?php echo htmlspecialchars($task['description']); ?></p>
                            <div class="deadline">
                                <i class="far fa-clock"></i> 
                                <?php echo $task['deadline'] ? date('d/m/Y', strtotime($task['deadline'])) : 'Pas de date limite'; ?>
                            </div>
                            <div class="task-actions">
                                <button class="btn btn-sm btn-outline-primary edit-task" 
                                        data-task-id="<?php echo $task['id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-task" 
                                        data-task-id="<?php echo $task['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Modal pour ajouter/modifier une tâche -->
    <div class="modal fade" id="taskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle tâche</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm">
                        <input type="hidden" id="taskId">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priorité</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="low">Basse</option>
                                <option value="medium">Moyenne</option>
                                <option value="high">Haute</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="to_do">À faire</option>
                                <option value="in_progress">En cours</option>
                                <option value="completed">Terminée</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="deadline" class="form-label">Date limite</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteneur pour les notifications -->
    <div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/pfe/assets/js/main.js"></script>
    <script src="/pfe/assets/js/tasks.js"></script>
</body>
</html>
