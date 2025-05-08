<?php
session_start();
require_once '../includes/lang.php';
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


// Fonction pour obtenir la classe d'icône selon le statut utilisateur
function getStatusIcon($status) {
    switch ($status) {
        case 'available':
            return '<i class="fas fa-circle text-success" title="' . __('Disponible') . '"></i>';
        case 'busy':
            return '<i class="fas fa-circle text-danger" title="' . __('Occupé') . '"></i>';
        case 'away':
            return '<i class="fas fa-circle text-warning" title="' . __('Absent') . '"></i>';
        default:
            return '<i class="fas fa-circle text-secondary" title="' . __('Inconnu') . '"></i>';
    }
}

// Calculer l'ancienneté du compte
$created_date = new DateTime($user['created_at']);
$now = new DateTime();
$interval = $created_date->diff($now);
$account_age = '';

if ($interval->y > 0) {
    $account_age = $interval->y . ' ' . ($interval->y > 1 ? __('ans') : __('an')) . ' ';
}
if ($interval->m > 0) {
    $account_age .= $interval->m . ' ' . __('mois') . ' ';
}
if ($interval->d > 0 && $interval->y == 0) {
    $account_age .= $interval->d . ' ' . ($interval->d > 1 ? __('jours') : __('jour')) . ' ';
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
                    <?php if ($current_user_id == $profile_user_id): ?>
                            <div class="mt-4 mb-3">
                                <a href="profile.php" class="btn btn-primary w-100 d-block">
                                    <i class="fas fa-user-edit me-2"></i>Modifier mon profil
                                </a>
                            </div>
                            <div>
                                <a href="messages.php?receiver=<?= $user['id'] ?>" class="btn btn-message w-100 d-block">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer un message
                                </a>
                            </div>
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