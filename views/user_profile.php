<?php
session_start();
require_once "../config/database.php";

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: /pfe/views/auth/login.php");
    exit();
}

// Vérification si l'ID de l'utilisateur à afficher est fourni
if (!isset($_GET['id'])) {
    header("Location: /pfe/views/messages.php");
    exit();
}

$profile_user_id = $_GET['id'];
$current_user_id = $_SESSION['user_id'];

// Création d'une instance de la classe Database
$database = new Database();
$pdo = $database->getConnection();

// Récupération des informations de l'utilisateur
$query = "SELECT id, username, email, created_at, profile_picture, status, role, last_login, plan, is_admin 
          FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $profile_user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si l'utilisateur n'existe pas, rediriger
if (!$user) {
    header("Location: /pfe/views/messages.php");
    exit();
}

// Récupération des préférences de l'utilisateur
$query = "SELECT theme, notifications_enabled, language, updated_at 
          FROM user_preferences WHERE user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $profile_user_id);
$stmt->execute();
$preferences = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupération des tâches de l'utilisateur
$query = "SELECT * FROM tasks WHERE assigned_to = :user_id ORDER BY due_date ASC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $profile_user_id);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des statistiques des tâches
$query = "SELECT 
            COUNT(*) as total_tasks,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
            SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_tasks,
            SUM(CASE WHEN status = 'to_do' THEN 1 ELSE 0 END) as to_do_tasks
          FROM tasks 
          WHERE assigned_to = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $profile_user_id);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupération des messages récents
$query = "SELECT m.id, m.content, m.created_at, m.is_read, u.username, u.profile_picture 
          FROM messages m 
          JOIN users u ON m.sent_by = u.id 
          WHERE m.user_id = :user_id 
          ORDER BY m.created_at DESC 
          LIMIT 5";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $profile_user_id);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des notifications récentes
$query = "SELECT n.title, n.message, n.type, n.created_at, nr.is_read
          FROM notifications n
          JOIN notification_recipients nr ON n.id = nr.notification_id
          WHERE nr.user_id = :user_id
          ORDER BY n.created_at DESC
          LIMIT 5";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $profile_user_id);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des activités d'administration (si l'utilisateur est admin)
$admin_activities = [];
if ($user['is_admin']) {
    $query = "SELECT aa.action, aa.details, aa.created_at
              FROM admin_activity aa
              WHERE aa.admin_id = :user_id
              ORDER BY aa.created_at DESC
              LIMIT 10";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $profile_user_id);
    $stmt->execute();
    $admin_activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupération des sous-tâches pour chaque tâche
$task_subtasks = [];
foreach ($tasks as $task) {
    $query = "SELECT id, title, completed FROM subtasks WHERE task_id = :task_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':task_id', $task['id']);
    $stmt->execute();
    $subtasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $task_subtasks[$task['id']] = $subtasks;
}

// Déterminer si c'est le profil de l'utilisateur actuel
$is_own_profile = ($profile_user_id == $current_user_id);

// Fonction pour obtenir un texte décrivant quand l'utilisateur était actif pour la dernière fois
function getLastActiveText($lastLogin) {
    if (empty($lastLogin)) {
        return "Jamais connecté";
    }
    
    $lastLoginTime = strtotime($lastLogin);
    $currentTime = time();
    $diff = $currentTime - $lastLoginTime;
    
    if ($diff < 60) { // moins d'une minute
        return "Actif à l'instant";
    } elseif ($diff < 3600) { // moins d'une heure
        $minutes = floor($diff / 60);
        return "Actif il y a " . $minutes . " minute" . ($minutes > 1 ? "s" : "");
    } elseif ($diff < 86400) { // moins d'un jour
        $hours = floor($diff / 3600);
        return "Actif il y a " . $hours . " heure" . ($hours > 1 ? "s" : "");
    } else { // plus d'un jour
        return "Dernière connexion le " . date('d/m/Y à H:i', $lastLoginTime);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/pfe/assets/images/gdt.png" type="image/x-icon">
    <title>Profil Utilisateur - TaskFlow</title>
    
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            padding: 30px;
            border-radius: 15px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        
        .stats-card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            margin: 30px;

            width: 90%;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .task-card {
            border-radius: 10px;
            border-left: 5px solid;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }
        
        .task-card:hover {
            transform: translateX(5px);
        }
        
        .task-to-do {
            border-left-color: #ffc107;
        }
        
        .task-in-progress {
            border-left-color: #17a2b8;
        }
        
        .task-completed {
            border-left-color: #28a745;
        }
        
        .badge-status {
            font-size: 0.8rem;
            padding: 0.35em 0.65em;
        }
        
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        
        .tab-content {
            padding: 20px 0;
            width: 100%;
           
           
        }
        .nav-tabs{
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        
        .nav-tabs .nav-link {
            border-radius: 10px 10px 0 0;
            padding: 10px 20px;
            font-weight: 500;
           background: #000;
            border: 1px solid #2575fc;
        }
        
        .nav-tabs .nav-link.active {
            border-color: #dee2e6 #dee2e6 #fff;
           background-color: #6a11cb;
            color: #2575fc;
        }
        
        .no-tasks-message {
            text-align: center;
            padding: 30px;
            color: #6c757d;
        }
        
        .btn-message {
            border-radius: 50px;
            padding: 8px 20px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }
        
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        
        .status-available {
            background-color: #28a745;
        }
        
        .status-busy {
            background-color: #dc3545;
        }
        
        .status-away {
            background-color: #ffc107;
        }
        
        .notification-item {
            padding: 10px;
            border-left: 4px solid;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .notification-info {
            border-left-color: #17a2b8;
        }
        
        .notification-success {
            border-left-color: #28a745;
        }
        
        .notification-warning {
            border-left-color: #ffc107;
        }
        
        .notification-error {
            border-left-color: #dc3545;
        }
        
        .message-item {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .message-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .message-user-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }
        
        .subtask-list {
            list-style: none;
            padding-left: 10px;
            margin-top: 10px;
        }
        
        .subtask-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .subtask-checkbox {
            margin-right: 8px;
        }
        
        .admin-activity-item {
            padding: 12px;
            border-left: 3px solid #6a11cb;
            margin-bottom: 12px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>

<body style="margin-top: 20px;">
    <?php include '../includes/navbar.php'; ?>

    <div class="container" style="margin-top: 100px;">
        <!-- En-tête du profil -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <img src="<?= !empty($user['profile_picture']) ? '/pfe/uploads/profile/' . $user['profile_picture'] : '/pfe/assets/images/default.png' ?>" 
                         alt="Photo de profil" class="profile-image">
                </div>
                <div class="col-md-7">
                    <h2><?= htmlspecialchars($user['username']) ?></h2>
                    <p>
                        <i class="fas fa-envelope me-2"></i><?= htmlspecialchars($user['email']) ?>
                    </p>
                    <p>
                        <?php 
                        $statusClass = '';
                        switch ($user['status']) {
                            case 'available':
                                $statusClass = 'status-available';
                                $statusText = 'Disponible';
                                break;
                            case 'busy':
                                $statusClass = 'status-busy';
                                $statusText = 'Occupé';
                                break;
                            case 'away':
                                $statusClass = 'status-away';
                                $statusText = 'Absent';
                                break;
                            default:
                                $statusClass = '';
                                $statusText = 'Inconnu';
                        }
                        ?>
                        <span class="status-indicator <?= $statusClass ?>"></span>
                        <?= $statusText ?> · <?= getLastActiveText($user['last_login']) ?>
                    </p>
                </div>
                <div class="col-md-3 text-end">
                    <?php if (!$is_own_profile): ?>
                    <a href="/pfe/views/send_direct_message.php?to=<?= $user['id'] ?>" class="btn btn-light btn-message">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer un message
                    </a>
                    <?php else: ?>
                    <a href="/pfe/views/profile.php" class="btn btn-light btn-message">
                        <i class="fas fa-user-edit me-2"></i>Modifier mon profil
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3><?= $stats['total_tasks'] ?? 0 ?></h3>
                        <p class="mb-0">Tâches totales</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-success text-white">
                    <div class="card-body text-center">
                        <h3><?= $stats['completed_tasks'] ?? 0 ?></h3>
                        <p class="mb-0">Tâches terminées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-info text-white">
                    <div class="card-body text-center">
                        <h3><?= $stats['in_progress_tasks'] ?? 0 ?></h3>
                        <p class="mb-0">Tâches en cours</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3><?= $stats['to_do_tasks'] ?? 0 ?></h3>
                        <p class="mb-0">Tâches à faire</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progression globale -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Progression globale</h5>
                <?php 
                $total = $stats['total_tasks'] ?? 0;
                $completed = $stats['completed_tasks'] ?? 0;
                $completion_percentage = ($total > 0) ? round(($completed / $total) * 100) : 0;
                ?>
                <div class="progress mb-2">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= $completion_percentage ?>%" 
                         aria-valuenow="<?= $completion_percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="text-end mb-0"><?= $completion_percentage ?>% complété</p>
            </div>
        </div>

        <!-- Navigation par onglets -->
        <ul class="nav nav-tabs" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tasks-tab" data-bs-toggle="tab" data-bs-target="#tasks" type="button" role="tab" aria-controls="tasks" aria-selected="true">
                    <i class="fas fa-tasks me-2"></i>Tâches
                </button>
            </li>
            
            <?php if ($user['is_admin']): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab" aria-controls="activity" aria-selected="false">
                  <i class="fas fa-chart-line me-2"></i>Activité Admin
                </button>
            </li>
            <?php endif; ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab" aria-controls="about" aria-selected="false">
                    <i class="fas fa-user me-2"></i>À propos
                </button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabsContent">
            <!-- Onglet des tâches -->
            <div class="tab-pane fade show active" id="tasks" role="tabpanel" aria-labelledby="tasks-tab">
                <?php if (count($tasks) > 0): ?>
                    <div class="row">
                        <?php foreach ($tasks as $task): 
                            $statusClass = '';
                            $statusBadge = '';
                            
                            switch($task['status']) {
                                case 'to_do':
                                    $statusClass = 'task-to-do';
                                    $statusBadge = '<span class="badge bg-warning badge-status">À faire</span>';
                                    break;
                                case 'in_progress':
                                    $statusClass = 'task-in-progress';
                                    $statusBadge = '<span class="badge bg-info badge-status">En cours</span>';
                                    break;
                                case 'completed':
                                    $statusClass = 'task-completed';
                                    $statusBadge = '<span class="badge bg-success badge-status">Terminée</span>';
                                    break;
                                default:
                                    $statusClass = '';
                                    $statusBadge = '<span class="badge bg-secondary badge-status">Non défini</span>';
                            }
                            
                            // Calcul du retard
                            $is_late = false;
                            $late_text = '';
                            if ($task['due_date']) {
                                $due_date = strtotime($task['due_date']);
                                $current_date = time();
                                if ($due_date < $current_date && $task['status'] != 'completed') {
                                    $is_late = true;
                                    $days_late = floor(($current_date - $due_date) / (60 * 60 * 24));
                                    $late_text = '<span class="badge bg-danger ms-2">En retard de ' . $days_late . ' jour(s)</span>';
                                }
                            }
                            
                            // Calcul de la priorité
                            $priority_class = '';
                            $priority_text = '';
                            switch($task['priority']) {
                                case 'low':
                                    $priority_class = 'text-success';
                                    $priority_text = '<i class="fas fa-arrow-down ' . $priority_class . '"></i> Basse';
                                    break;
                                case 'medium':
                                    $priority_class = 'text-warning';
                                    $priority_text = '<i class="fas fa-equals ' . $priority_class . '"></i> Moyenne';
                                    break;
                                case 'high':
                                    $priority_class = 'text-danger';
                                    $priority_text = '<i class="fas fa-arrow-up ' . $priority_class . '"></i> Haute';
                                    break;
                            }
                            
                            // Récupération des sous-tâches
                            $subtasks = $task_subtasks[$task['id']] ?? [];
                            $total_subtasks = count($subtasks);
                            $completed_subtasks = 0;
                            
                            foreach ($subtasks as $subtask) {
                                if ($subtask['completed']) {
                                    $completed_subtasks++;
                                }
                            }
                            
                            $subtask_progress = ($total_subtasks > 0) ? round(($completed_subtasks / $total_subtasks) * 100) : 0;
                        ?>
                        <div class="col-md-6">
                            <div class="card task-card <?= $statusClass ?>">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($task['title']) ?></h5>
                                        <div>
                                            <?= $statusBadge ?>
                                            <?= $late_text ?>
                                        </div>
                                    </div>
                                    <p class="card-text"><?= htmlspecialchars(substr($task['description'], 0, 100)) . (strlen($task['description']) > 100 ? '...' : '') ?></p>
                                    
                                    <?php if ($total_subtasks > 0): ?>
                                    <div class="mb-3">
                                        <small class="text-muted">Sous-tâches: <?= $completed_subtasks ?>/<?= $total_subtasks ?></small>
                                        <div class="progress mt-1" style="height: 5px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: <?= $subtask_progress ?>%" 
                                                aria-valuenow="<?= $subtask_progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        
                                        <ul class="subtask-list">
                                            <?php foreach ($subtasks as $subtask): ?>
                                            <li class="subtask-item">
                                                <input type="checkbox" class="subtask-checkbox" <?= $subtask['completed'] ? 'checked' : '' ?> disabled>
                                                <span <?= $subtask['completed'] ? 'style="text-decoration: line-through;"' : '' ?>>
                                                    <?= htmlspecialchars($subtask['title']) ?>
                                                </span>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><?= $priority_text ?></small>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            <?= $task['due_date'] ? date('d/m/Y', strtotime($task['due_date'])) : 'Pas de date limite' ?>
                                        </small>
                                    </div>
                                    
                                    <?php if ($task['assigned_by']): ?>
                                    <div class="mt-2 text-end">
                                        <small class="text-muted">
                                            <i class="fas fa-user-check me-1"></i>
                                            Assignée le <?= date('d/m/Y', strtotime($task['assigned_at'])) ?>
                                        </small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-tasks-message">
                        <i class="fas fa-clipboard-list fa-3x mb-3 text-muted"></i>
                        <h4>Aucune tâche disponible</h4>
                        <p>Cet utilisateur n'a pas encore de tâches assignées.</p>
                    </div>
                <?php endif; ?>
            </div>

           

          
                    <!-- Onglet d'activité administrateur -->
                    <?php if ($user['is_admin']): ?>
                    <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                        <?php if (count($admin_activities) > 0): ?>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Activités administratives récentes</h5>
                                    <?php foreach ($admin_activities as $activity): ?>
                                    <div class="admin-activity-item">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-0"><?= htmlspecialchars($activity['action']) ?></h6>
                                            <small class="text-muted"><?= date('d/m/Y à H:i', strtotime($activity['created_at'])) ?></small>
                                        </div>
                                        <p class="mb-0"><?= htmlspecialchars($activity['details']) ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="no-tasks-message">
                                <i class="fas fa-chart-pie fa-3x mb-3 text-muted"></i>
                                <h4>Aucune activité administrative</h4>
                                <p>Aucune activité administrative n'a été enregistrée pour cet utilisateur.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
        
                    <!-- Onglet à propos -->
                    <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="about-tab">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Informations du compte</h5>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-user me-2"></i>Nom d'utilisateur</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <?= htmlspecialchars($user['username']) ?>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-envelope me-2"></i>Email</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <?= htmlspecialchars($user['email']) ?>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-users-cog me-2"></i>Rôle</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <?php
                                            $role_text = '';
                                            switch($user['role']) {
                                                case 'admin':
                                                    $role_text = 'Administrateur';
                                                    break;
                                                case 'manager':
                                                    $role_text = 'Manager';
                                                    break;
                                                case 'user':
                                                    $role_text = 'Utilisateur';
                                                    break;
                                                default:
                                                    $role_text = 'Non défini';
                                            }
                                        ?>
                                        <?= $role_text ?>
                                        <?php if ($user['is_admin']): ?>
                                        <span class="badge bg-danger ms-2">Admin</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-calendar-alt me-2"></i>Inscrit depuis</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-clock me-2"></i>Dernière connexion</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <?= $user['last_login'] ? date('d/m/Y à H:i', strtotime($user['last_login'])) : 'Jamais connecté' ?>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-gem me-2"></i>Plan</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <?php
                                            $plan_text = '';
                                            $plan_badge = '';
                                            switch($user['plan']) {
                                                case 'free':
                                                    $plan_text = 'Gratuit';
                                                    $plan_badge = '<span class="badge bg-secondary ms-2">Free</span>';
                                                    break;
                                                case 'premium':
                                                    $plan_text = 'Premium';
                                                    $plan_badge = '<span class="badge bg-warning ms-2">Premium</span>';
                                                    break;
                                                case 'enterprise':
                                                    $plan_text = 'Enterprise';
                                                    $plan_badge = '<span class="badge bg-info ms-2">Enterprise</span>';
                                                    break;
                                                default:
                                                    $plan_text = 'Non défini';
                                                    $plan_badge = '';
                                            }
                                        ?>
                                        <?= $plan_text ?> <?= $plan_badge ?>
                                    </div>
                                </div>
                                
                                <?php if ($preferences): ?>
                                <h5 class="card-title mt-4 mb-3">Préférences</h5>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-paint-brush me-2"></i>Thème</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <?= ucfirst($preferences['theme']) ?>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-bell me-2"></i>Notifications</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <?= $preferences['notifications_enabled'] ? 'Activées' : 'Désactivées' ?>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-language me-2"></i>Langue</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <?php
                                            $language_text = '';
                                            switch($preferences['language']) {
                                                case 'fr':
                                                    $language_text = 'Français';
                                                    break;
                                                case 'en':
                                                    $language_text = 'Anglais';
                                                    break;
                                                case 'es':
                                                    $language_text = 'Espagnol';
                                                    break;
                                                default:
                                                    $language_text = $preferences['language'];
                                            }
                                        ?>
                                        <?= $language_text ?>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-edit me-2"></i>Dernière mise à jour</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <?= date('d/m/Y à H:i', strtotime($preferences['updated_at'])) ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Activation des tooltips Bootstrap
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    })
                });
            </script>
        </body>
        </html>