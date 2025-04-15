<!-- dashboard.php -->
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
    <link rel="shortcut icon" href="/pfe/assets/images/gdt.png" type="image/x-icon">
    <title>Tableau de bord - TaskManager</title>
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/dashboard.css">

</head>

<body>
    <?php include "../includes/navbar.php"; ?>

    <div class="container-fluid py-4 " style="margin-top: 100px;">
        <h2 class="text-center mb-4" style="font-family: 'Courier New', Courier, monospace;"> Tableau de bord</h2>
        <p class="text-center mb-4">Suivez vos tâches et votre progression grâce à un tableau de bord clair et
            interactif.</p>
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

        <div class="d-flex justify-content-between align-items-center mb-4 mx-3"
            style=" background-image: linear-gradient(to right, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)); border-radius: 10px; padding: 5px; z-index: 10;">
            <h5> Mes tâches :</h5>
            <div>
              
                <button id="addTaskBtn" class="btn btn-primary">
                    <i class="fas fa-plus"></i> 
                    <span class="d-none d-sm-inline">Nouvelle tâche</span>
                </button>
            </div>
        </div>
    </div>



    <!-- Tableau Kanban -->

    <div class="row mx-3">
        <div class="col-md-4 tache-a-faire">
            <div style="background: rgba(206, 12, 12, 0.3); border-radius: 10px; padding: 10px; text-align: center; margin-bottom:-10px;">
                <h5 style="display: inline-block; margin:0 auto;"><i class="fas fa-list" style="color:red;"></i> &nbsp; À faire</h5>
            </div>

            <div class="task-board" style="min-width: 110%; position: relative; right: 18px;">
                <div class="task-column" style="min-height: 400px; " data-status="to_do" id="todoTasks">
                    
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

            </div>

        </div>

        <div class="col-md-4 tahe-en-cours">

            <div style="background: rgba(220, 248, 8, 0.33); border-radius: 10px; padding: 10px; text-align: center; margin-bottom: 10px;">
                <h5 style="display: inline-block; margin: 0 auto;"><i class="fas fa-spinner" style="color:rgb(255, 225, 0);"></i>&nbsp; En cours</h5>

            </div>

            <div class="task-column mb-3"style="min-height: 400px; " data-status="in_progress" id="inProgressTasks">
               
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
        </div>

        <div class="col-md-4 tache-terminer">
            <div style="background: rgba(4, 251, 66, 0.34); border-radius: 10px; padding: 10px; text-align: center; margin-bottom: 10px;">
                <h5 style="display: inline-block; margin: 0 auto;"><i class="fas fa-check" style="color:green;"></i>&nbsp; Terminées</h5>

            </div>

            <div class="task-column mb-3"  style="min-height: 400px; "  data-status="completed" id="completedTasks" >
              
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

    <!-- Footer -->
    <footer style="margin-top: 20px;">
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-section">
                    <h5>À propos de TaskFlow</h5>
                    <p>TaskFlow est une solution moderne de gestion de tâches conçue pour optimiser votre productivité
                        et simplifier votre organisation quotidienne.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/3ab2u.art"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/3ab2u.art"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/channel/UCn2E5b4o2M2XyKw2oP4pZyA"><i
                                class="fab fa-youtube"></i></a>
                        <a href="https://github.com/3ab2"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="col-md-4 footer-section">
                    <h5>Liens Rapides</h5>
                    <ul class="footer-links">
                        <li><a href="/pfe/views/dashboard.php">Tableau de bord</a></li>

                        <li><a href="/pfe/FAQ.php">FAQ</a></li>
                        <li><a href="/pfe/support.php">Support</a></li>
                    </ul>
                </div>
                <div class="col-md-4 footer-section">
                    <h5>Contact</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-envelope me-2"></i> elkarchabdo@gmail.com</li>
                        <li><i class="fas fa-phone me-2"></i> +212 789 123 456</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> C I T </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2021 - <?php echo date('Y '); ?> TaskFlow. Tous droits réservés.</p>
            </div>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/pfe/assets/js/main.js"></script>
    <script src="/pfe/assets/js/tasks.js"></script>
</body>

</html>