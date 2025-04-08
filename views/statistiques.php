<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /pfe/views/auth/login.php');
    exit();
}

require_once "../controllers/TaskController.php";
require_once "../controllers/AdminController.php";
require_once "../controllers/MessageController.php";
require_once "../config/database.php";

$taskController = new TaskController();
$adminController = new AdminController();
$messageController = new MessageController();

// Récupération des statistiques des messages
$database = new Database();
$pdo = $database->getConnection();

// Récupérer les messages par mois
$query = "SELECT 
            MONTH(created_at) as month,
            COUNT(*) as total,
            SUM(CASE WHEN user_id = :user_id THEN 1 ELSE 0 END) as sent,
            SUM(CASE WHEN user_id != :user_id THEN 1 ELSE 0 END) as received
          FROM messages 
          WHERE YEAR(created_at) = YEAR(CURRENT_DATE)
          GROUP BY MONTH(created_at)
          ORDER BY month";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$messageStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparer les données pour le graphique
$months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
$sentData = array_fill(0, 12, 0);
$receivedData = array_fill(0, 12, 0);

foreach ($messageStats as $stat) {
    $monthIndex = $stat['month'] - 1;
    $sentData[$monthIndex] = $stat['sent'];
    $receivedData[$monthIndex] = $stat['received'];
}

// Récupérer le nombre total de messages
$totalQuery = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN user_id = :user_id THEN 1 ELSE 0 END) as total_sent,
                SUM(CASE WHEN user_id != :user_id THEN 1 ELSE 0 END) as total_received
              FROM messages";
$totalStmt = $pdo->prepare($totalQuery);
$totalStmt->bindParam(':user_id', $_SESSION['user_id']);
$totalStmt->execute();
$totalStats = $totalStmt->fetch(PDO::FETCH_ASSOC);

$statsResult = $taskController->getTaskStats();
$stats = $statsResult['success'] ? $statsResult['data'] : [];

// Récupérer les statistiques des notifications
$notificationStats = $adminController->getNotificationStatistics();

$total = $stats['total'] ?? 0;
$in_progress = $stats['in_progress'] ?? 0;
$completed = $stats['completed'] ?? 0;
$overdue = $stats['overdue'] ?? 0;

$showEqualImage = ($total == $completed && $total > 0);

// Calculer les pourcentages pour les conseils
$total_tasks = $total > 0 ? $total : 1; // Éviter la division par zéro
$completed_percentage = ($completed / $total_tasks) * 100;
$in_progress_percentage = ($in_progress / $total_tasks) * 100;
$overdue_percentage = ($overdue / $total_tasks) * 100;

// Générer des conseils basés sur les statistiques
$task_advice = [];

if ($overdue > 0) {
    $task_advice[] = "Vous avez $overdue tâche(s) en retard. Priorisez ces tâches pour éviter les retards supplémentaires.";
}

if ($in_progress_percentage > 50) {
    $task_advice[] = "Plus de 50% de vos tâches sont en cours. Considérez de terminer certaines tâches avant d'en commencer de nouvelles.";
}

if ($completed_percentage < 30) {
    $task_advice[] = "Votre taux de complétion est faible ($completed_percentage%). Concentrez-vous sur la finalisation des tâches en cours.";
} elseif ($completed_percentage > 80) {
    $task_advice[] = "Excellent travail ! Vous avez complété $completed_percentage% de vos tâches. Continuez ainsi !";
}

if ($total == 0) {
    $task_advice[] = "Vous n'avez aucune tâche enregistrée. Commencez par créer de nouvelles tâches pour organiser votre travail.";
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - TaskManager</title>
    
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php include "../includes/navbar.php"; ?>

    <div class="container py-4 mt-5 " style="margin-top: 10px;">
        <h2 class="text-center mb-4"> Statistiques </h2>
        <p class="text-center mb-4">Suivez vos tâches et votre progression en temps reel.</p>

        <!-- Message d'encouragement conditionnel -->
        <?php if ($overdue > 0 || $in_progress > 0): ?>
            <div class="text-center my-4 p-4" style="background: linear-gradient(135deg, #ffd3a5, #fd6585); border-radius: 15px; box-shadow: 0 8px 32px rgba(0,0,0,0.15); position: relative; overflow: hidden;">
                <!-- Icône animée -->
                <div class="encouragement-icon" style="animation: bounce 2s infinite;">
                    <i class="fas fa-hand-holding-heart fa-4x text-white mb-3" style="filter: drop-shadow(0 4px 8px rgba(255,255,255,0.4));"></i>
                </div>

                <!-- Messages multilingues avec animation -->
                <div class="message-container">
                    <!-- Message en Français -->
                    <div class="mb-3">
                        <h3 class="text-white" style="font-size: 1.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                            <span style="animation: sparkle 2s infinite;">✨</span> 
                            sois courageux
                            <span style="animation: sparkle 2s infinite;">✨</span>
                        </h3>
                        <p class="text-white" style="font-size: 1.2rem;">
                             Continuez vos efforts ! Chaque petite avancée vous rapproche de vos objectifs. Vous pouvez le faire ! 💪
                        </p>
                    </div>

                    <div class="mt-3 text-white">
                        <small>
                            <?php 
                            if ($overdue > 0) {
                                echo "Vous avez $overdue tâche(s) en retard. ";
                            }
                            if ($in_progress > 0) {
                                echo "Et $in_progress tâche(s) en cours. ";
                            }
                            ?>
                        </small>
                    </div>
                </div>

                <!-- Effet de brillance -->
                <div class="shine"></div>
            </div>
        <?php endif; ?>

        <!-- Affichage conditionnel d'une image si total == completed -->
        <?php if ($showEqualImage): ?>
            <div class="text-center my-4 p-4 celebration-container" style="background: linear-gradient(135deg, #6dd5ed, #2193b0); border-radius: 15px; box-shadow: 0 8px 32px rgba(0,0,0,0.15); position: relative; overflow: hidden;">
                <!-- Effet de confettis -->
                <div class="confetti-container"></div>
                
                <!-- Icône animée -->
                <div class="trophy-container" style="animation: bounce 2s infinite;">
                    <i class="fas fa-trophy fa-4x text-warning mb-3" style="filter: drop-shadow(0 4px 8px rgba(255,215,0,0.4));"></i>
                </div>

                <!-- Message de félicitations avec animation de texte -->
                <div class="message-container">
                    <h3 class="text-white mb-3" style="font-size: 1.8rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); animation: slideIn 1s ease-out;">
                        <span style="animation: sparkle 2s infinite;">✨</span> 
                        Félicitations ! 
                        <span style="animation: sparkle 2s infinite;">✨</span>
                    </h3>
                    <p class="text-white" style="font-size: 1.3rem; opacity: 0; animation: fadeIn 1s ease-out forwards 0.5s;">
                        Vous avez complété toutes vos tâches ! 🎉
                    </p>
                </div>

                <!-- Effet de brillance -->
                <div class="shine"></div>
            </div>

            <style>
                @keyframes bounce {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-15px); }
                }
                @keyframes slideIn {
                    from { transform: translateX(-100px); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes fadeIn {
                    to { opacity: 1; }
                }
                @keyframes sparkle {
                    0%, 100% { opacity: 1; transform: scale(1); }
                    50% { opacity: 0.5; transform: scale(1.2); }
                }
                .shine {
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 50%;
                    height: 100%;
                    background: linear-gradient(
                        90deg,
                        transparent,
                        rgba(255,255,255,0.3),
                        transparent
                    );
                    animation: shine 3s infinite;
                }
                @keyframes shine {
                    to { left: 200%; }
                }
                .celebration-container {
                    transition: transform 0.3s ease;
                }
                .celebration-container:hover {
                    transform: scale(1.02);
                }
            </style>

            <script>
                // Création des confettis
                function createConfetti() {
                    const confettiContainer = document.querySelector('.confetti-container');
                    for(let i = 0; i < 50; i++) {
                        const confetti = document.createElement('div');
                        confetti.style.cssText = `
                            position: absolute;
                            width: 10px;
                            height: 10px;
                            background: ${['#ff0', '#f0f', '#0ff', '#ff4081', '#00e676'][Math.floor(Math.random() * 5)]};
                            left: ${Math.random() * 100}%;
                            animation: fall ${Math.random() * 3 + 2}s linear infinite;
                            transform: rotate(${Math.random() * 360}deg);
                        `;
                        confettiContainer.appendChild(confetti);
                    }
                }
                createConfetti();
            </script>
        <?php endif; ?>

        <!-- Diagramme -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Statistiques des Tâches</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                            <canvas id="taskChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Conseils de Productivité</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($task_advice)): ?>
                            <ul class="list-group">
                                <?php foreach ($task_advice as $advice): ?>
                                    <li class="list-group-item">
                                        <i class="fas fa-lightbulb text-warning me-2"></i>
                                        <?php echo htmlspecialchars($advice); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">Votre gestion des tâches est optimale !</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nouveau graphique pour les notifications -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Statistiques des Notifications</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                            <canvas id="notificationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Conseils et Recommandations</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($notificationStats['advice'])): ?>
                            <ul class="list-group">
                                <?php foreach ($notificationStats['advice'] as $advice): ?>
                                    <li class="list-group-item">
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        <?php echo htmlspecialchars($advice); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">Aucun conseil particulier pour le moment.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nouveau graphique pour les messages -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Statistiques des Messages</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                            <canvas id="messageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Statut des Messages</h5>
                    </div>
                    <div class="card-body">
                        <div class="message-stats">
                            <div class="stat-item">
                                <i class="fas fa-paper-plane text-primary"></i>
                                <span>Messages envoyés: <span id="sentCount"><?php echo $totalStats['total_sent']; ?></span></span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-inbox text-success"></i>
                                <span>Messages reçus: <span id="receivedCount"><?php echo $totalStats['total_received']; ?></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const taskCtx = document.getElementById('taskChart').getContext('2d');
        new Chart(taskCtx, {
            type: 'doughnut',
            data: {
                labels: ['À faire', 'En retard', 'En cours', 'Terminées'],
                datasets: [{
                    data: [
                        <?php echo $total - ($in_progress + $completed + $overdue); ?>,
                        <?php echo $overdue; ?>,
                        <?php echo $in_progress; ?>,
                        <?php echo $completed; ?>
                    ],
                    backgroundColor: [
                        '#6c757d', // À faire - Gris
                        '#dc3545', // En retard - Rouge
                        '#ffc107', // En cours - Jaune
                        '#28a745'  // Terminées - Vert
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 12
                            },
                            padding: 20
                        }
                    },
                    title: {
                        display: true,
                        text: 'Répartition des Tâches',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });

        // Configuration du graphique des notifications
        const notificationCtx = document.getElementById('notificationChart').getContext('2d');
        new Chart(notificationCtx, {
            type: 'doughnut',
            data: {
                labels: ['Information', 'Avertissement', 'Erreur', 'Succès'],
                datasets: [{
                    data: [
                        <?php echo $notificationStats['stats']['info_count']; ?>,
                        <?php echo $notificationStats['stats']['warning_count']; ?>,
                        <?php echo $notificationStats['stats']['error_count']; ?>,
                        <?php echo $notificationStats['stats']['success_count']; ?>
                    ],
                    backgroundColor: [
                        '#17a2b8', // Info - Bleu
                        '#ffc107', // Warning - Jaune
                        '#dc3545', // Error - Rouge
                        '#28a745'  // Success - Vert
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 12
                            },
                            padding: 20
                        }
                    },
                    title: {
                        display: true,
                        text: 'Répartition des Notifications',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    }
                },
                cutout: '60%'
            }
        });

        // Configuration du graphique des messages
        const messageCtx = document.getElementById('messageChart').getContext('2d');
        const messageChart = new Chart(messageCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [
                    {
                        label: 'Messages envoyés',
                        data: <?php echo json_encode($sentData); ?>,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Messages reçus',
                        data: <?php echo json_encode($receivedData); ?>,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 12
                            },
                            padding: 20
                        }
                    },
                    title: {
                        display: true,
                        text: 'Progression des Messages',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value} message(s)`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value + ' message(s)';
                            }
                        }
                    }
                }
            }
        });
    </script>

    <style>
        .chart-container {
            min-height: 300px;
            margin: 20px 0;
        }

        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: 15px;
        }

        .card-title {
            margin: 0;
            color: #333;
            font-weight: 600;
        }

        .list-group-item {
            border-left: none;
            border-right: none;
            padding: 12px 20px;
        }

        .list-group-item i {
            font-size: 1.1em;
        }

        .task-status-card {
            transition: transform 0.3s ease;
        }

        .task-status-card:hover {
            transform: translateY(-5px);
        }

        .list-group-item {
            border-left: none;
            border-right: none;
            padding: 12px 20px;
            transition: background-color 0.3s ease;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .message-stats {
            padding: 15px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .stat-item:hover {
            transform: translateX(5px);
        }

        .stat-item i {
            font-size: 1.5rem;
            margin-right: 15px;
        }

        .stat-item span {
            font-size: 1.1rem;
            color: #333;
        }
    </style>

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
                <p>&copy; <?php echo date('Y '); ?> TaskFlow. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>

</html>
