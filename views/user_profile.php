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
    header("Location: /pfe/views/dashboard.php");
    exit();
}

$profile_user_id = $_GET['id'];
$current_user_id = $_SESSION['user_id'];

// Création d'une instance de la classe Database
$database = new Database();
$pdo = $database->getConnection();

// Récupération des informations de l'utilisateur
$query = "SELECT id, username, email, role, status, profile_picture, created_at, is_admin, last_login, plan 
          FROM users 
          WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $profile_user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: /pfe/views/dashboard.php");
    exit();
}

// Récupération des tâches assignées à l'utilisateur
$query = "SELECT id, title, description, status, priority, due_date, deadline, created_at 
          FROM tasks 
          WHERE assigned_to = :user_id 
          ORDER BY due_date ASC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $profile_user_id);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcul du pourcentage de progression pour chaque statut de tâche
$task_count = count($tasks);
$completed_count = 0;
$in_progress_count = 0;
$to_do_count = 0;

foreach ($tasks as $task) {
    if ($task['status'] == 'completed') {
        $completed_count++;
    } elseif ($task['status'] == 'in_progress') {
        $in_progress_count++;
    } elseif ($task['status'] == 'to_do') {
        $to_do_count++;
    }
}

$completed_percentage = $task_count > 0 ? round(($completed_count / $task_count) * 100) : 0;
$in_progress_percentage = $task_count > 0 ? round(($in_progress_count / $task_count) * 100) : 0;
$to_do_percentage = $task_count > 0 ? round(($to_do_count / $task_count) * 100) : 0;

// Fonction pour convertir le statut en texte français
function getStatusLabel($status) {
    switch ($status) {
        case 'to_do':
            return 'À faire';
        case 'in_progress':
            return 'En cours';
        case 'completed':
            return 'Terminée';
        default:
            return $status;
    }
}

// Fonction pour convertir la priorité en texte français
function getPriorityLabel($priority) {
    switch ($priority) {
        case 'low':
            return 'Basse';
        case 'medium':
            return 'Moyenne';
        case 'high':
            return 'Haute';
        default:
            return $priority;
    }
}

// Fonction pour obtenir la classe de couleur selon la priorité
function getPriorityClass($priority) {
    switch ($priority) {
        case 'low':
            return 'text-success';
        case 'medium':
            return 'text-warning';
        case 'high':
            return 'text-danger';
        default:
            return '';
    }
}

// Fonction pour obtenir la classe de couleur selon le statut
function getStatusClass($status) {
    switch ($status) {
        case 'to_do':
            return 'bg-danger';
        case 'in_progress':
            return 'bg-warning';
        case 'completed':
            return 'bg-success';
        default:
            return 'bg-secondary';
    }
}

// Fonction pour obtenir la classe d'icône selon le statut utilisateur
function getStatusIcon($status) {
    switch ($status) {
        case 'available':
            return '<i class="fas fa-circle text-success" title="Disponible"></i>';
        case 'busy':
            return '<i class="fas fa-circle text-danger" title="Occupé"></i>';
        case 'away':
            return '<i class="fas fa-circle text-warning" title="Absent"></i>';
        default:
            return '<i class="fas fa-circle text-secondary" title="Inconnu"></i>';
    }
}

// Calculer l'ancienneté du compte
$created_date = new DateTime($user['created_at']);
$now = new DateTime();
$interval = $created_date->diff($now);
$account_age = '';

if ($interval->y > 0) {
    $account_age = $interval->y . ' an' . ($interval->y > 1 ? 's' : '') . ' ';
}
if ($interval->m > 0) {
    $account_age .= $interval->m . ' mois ';
}
if ($interval->d > 0 && $interval->y == 0) {
    $account_age .= $interval->d . ' jour' . ($interval->d > 1 ? 's' : '') . ' ';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/pfe/assets/images/gdt.png" type="image/x-icon">
    <title>Profil de <?= htmlspecialchars($user['username']) ?> - TaskFlow</title>

    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #4776E6, #8E54E9);
            color: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.85rem;
            margin-left: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-bottom: none;
            border-radius: 16px 16px 0 0 !important;
            padding: 20px;
        }

        .task-item {
            border-left: 5px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .task-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .task-item.priority-high {
            border-left-color: #dc3545;
        }

        .task-item.priority-medium {
            border-left-color: #ffc107;
        }

        .task-item.priority-low {
            border-left-color: #28a745;
        }

        .progress {
            height: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .about-section {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .user-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .user-info-item i {
            width: 25px;
            margin-right: 10px;
            color: #6c757d;
        }

        .btn-message {
            background: linear-gradient(135deg, #4776E6, #8E54E9);
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .btn-message:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .task-progress-container {
            margin-bottom: 30px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .task-count {
            background-color: rgba(0, 0, 0, 0.1);
            padding: 2px 8px;
            border-radius: 50px;
            font-size: 0.8rem;
            margin-left: 5px;
        }

        .no-tasks-message {
            padding: 20px;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container" style="margin-top: 100px;">
        <div class="profile-header d-flex flex-column flex-md-row align-items-center">
            <div class="text-center me-md-4 mb-4 mb-md-0">
                <img src="/pfe/uploads/profile/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Photo de profil" class="profile-picture mb-3">
                <div class="mt-3">
                    <?php if ($current_user_id != $profile_user_id): ?>
                        <a href="messages.php?receiver=<?= $user['id'] ?>" class="btn btn-message">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer un message
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex-grow-1">
                <h2 class="mb-0 d-flex align-items-center">
                    <?= htmlspecialchars($user['username']) ?>
                    <span class="status-badge ms-2">
                        <?= getStatusIcon($user['status']) ?> 
                        <?= ucfirst($user['status']) ?>
                    </span>
                </h2>
                <p class="text-white-50"><?= $user['role'] == 'admin' ? 'Administrateur' : 'Utilisateur' ?> 
                    <?= $user['plan'] == 'premium' ? '<span class="badge bg-warning">Premium</span>' : '' ?>
                </p>
                <div class="user-info mt-4">
                    <div class="user-info-item">
                        <i class="fas fa-envelope"></i>
                        <span><?= htmlspecialchars($user['email']) ?></span>
                    </div>
                    <div class="user-info-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Membre depuis <?= $account_age ?></span>
                    </div>
                    <?php if ($user['last_login']): ?>
                    <div class="user-info-item">
                        <i class="fas fa-clock"></i>
                        <span>Dernière connexion: <?= date('d/m/Y à H:i', strtotime($user['last_login'])) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4><i class="fas fa-tasks me-2"></i>Tâches assignées (<?= count($tasks) ?>)</h4>
                    </div>
                    <div class="card-body">
                        <?php if (count($tasks) > 0): ?>
                            <div class="task-progress-container">
                                <div class="progress-label">
                                    <span>Terminées</span>
                                    <span><?= $completed_percentage ?>%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= $completed_percentage ?>%" aria-valuenow="<?= $completed_percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                
                                <div class="progress-label mt-3">
                                    <span>En cours</span>
                                    <span><?= $in_progress_percentage ?>%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $in_progress_percentage ?>%" aria-valuenow="<?= $in_progress_percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                
                                <div class="progress-label mt-3">
                                    <span>À faire</span>
                                    <span><?= $to_do_percentage ?>%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $to_do_percentage ?>%" aria-valuenow="<?= $to_do_percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <h5>Liste des tâches</h5>
                                <?php foreach ($tasks as $task): ?>
                                    <div class="task-item priority-<?= $task['priority'] ?>">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0"><?= htmlspecialchars($task['title']) ?></h6>
                                            <span class="badge <?= getStatusClass($task['status']) ?>"><?= getStatusLabel($task['status']) ?></span>
                                        </div>
                                        
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-calendar-alt me-1"></i> Échéance: 
                                            <?= $task['due_date'] ? date('d/m/Y', strtotime($task['due_date'])) : 'Non définie' ?>
                                        </p>
                                        
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-flag me-1 <?= getPriorityClass($task['priority']) ?>"></i> 
                                            Priorité: <?= getPriorityLabel($task['priority']) ?>
                                        </p>
                                        
                                        <?php if ($task['description']): ?>
                                            <div class="mt-2">
                                                <p class="mb-0 small"><?= nl2br(htmlspecialchars($task['description'])) ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-tasks-message">
                                <i class="fas fa-tasks fa-3x mb-3 text-muted"></i>
                                <p>Aucune tâche n'est actuellement assignée à cet utilisateur.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-user me-2"></i>À propos</h4>
                    </div>
                    <div class="card-body">
                        <div class="about-section">
                            <h5 class="mb-3">Informations générales</h5>
                            <div class="user-info-item">
                                <i class="fas fa-chart-line"></i>
                                <span>Performance: <?= $completed_percentage ?>% de tâches terminées</span>
                            </div>
                            <div class="user-info-item">
                                <i class="fas fa-clipboard-list"></i>
                                <span><?= $task_count ?> tâches attribuées</span>
                            </div>
                            <div class="user-info-item">
                                <i class="fas fa-clock"></i>
                                <span><?= $in_progress_count ?> tâches en cours</span>
                            </div>
                            <div class="user-info-item">
                                <i class="fas fa-check-circle"></i>
                                <span><?= $completed_count ?> tâches terminées</span>
                            </div>
                            <div class="user-info-item">
                                <i class="fas fa-times-circle"></i>
                                <span><?= $to_do_count ?> tâches à faire</span>
                            </div>
                        </div>

                        <?php if ($current_user_id == $profile_user_id): ?>
                            <div class="mt-4">
                                <a href="profile.php" class="btn btn-primary w-100">
                                    <i class="fas fa-user-edit me-2"></i>Modifier mon profil
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($user['role'] == 'admin'): ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h4><i class="fas fa-shield-alt me-2"></i>Informations administratives</h4>
                    </div>
                    <div class="card-body">
                        <div class="user-info-item">
                            <i class="fas fa-star"></i>
                            <span>Niveau d'administration: <?= $user['admin_level'] ?></span>
                        </div>
                        <div class="user-info-item">
                            <i class="fas fa-users-cog"></i>
                            <span>Rôle: Administrateur</span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="margin-top: 50px;">
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-section">
                    <h5>À propos de TaskFlow</h5>
                    <p>TaskFlow est une solution moderne de gestion de tâches conçue pour optimiser votre productivité
                        et simplifier votre organisation quotidienne.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/3ab2u.art"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/3ab2u.art"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/channel/UCn2E5b4o2M2XyKw2oP4pZyA"><i class="fab fa-youtube"></i></a>
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
                <p>&copy; <?php echo date('Y'); ?> TaskFlow. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Scroll to top on page load
        window.onload = function() {
            window.scrollTo(0, 0);
        };
    </script>
</body>
</html>