<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

$admin = new AdminController();
if (!$admin->isAdmin()) {
    header('Location: /pfe/views/auth/login.php');
    exit();
}

// Récupérer les statistiques de la même manière que dans dashboard.php
$users = $admin->getAllUsers();
$tasks = $admin->getAllTasks();
$messages = $admin->getAllMessages();

// Calculer les statistiques à partir des données complètes
$userStats = [
    'total' => count($users),
    'growth' => 15 // valeur par défaut ou à calculer
];

$taskStats = [
    'total' => count($tasks),
    'in_progress' => 0,
    'completed' => 0,
    'pending' => 0,
    'growth' => 8 // valeur par défaut ou à calculer
];

// Compter les tâches par statut
foreach ($tasks as $task) {
    if ($task['status'] === 'in_progress') {
        $taskStats['in_progress']++;
    } elseif ($task['status'] === 'completed') {
        $taskStats['completed']++;
    } elseif ($task['status'] === 'pending') {
        $taskStats['pending']++;
    }
}

$messageStats = [
    'total' => count($messages),
    'growth' => 12 // valeur par défaut ou à calculer
];

// Récupérer les statistiques des notifications
$notificationStats = $admin->getNotificationStatistics();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/pfe/assets/images/admin_logo.png" type="image/x-icon">
    <title>Taskflow Admin - Statistiques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="/pfe/assets/css/taskflow-custom.css" rel="stylesheet">
    <?php include '../includes/admin_style.php'; ?>
    <style>
        .stats-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('/pfe/assets/images/pattern.svg') repeat;
            opacity: 0.1;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.8;
            position: relative;
            z-index: 1;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .stats-label {
            font-size: 1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.07);
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
        }

        .chart-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #555;
        }

        .chart-actions {
            display: flex;
            gap: 0.5rem;
        }

        .chart-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            background: #f3f5f9;
            color: #555;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .chart-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
        }

        .chart-btn.active {
            background: var(--primary-color);
            color: white;
        }

        .trend-indicator {
            display: inline-flex;
            align-items: center;
            font-size: 0.875rem;
            margin-left: 0.5rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
        }

        .trend-up {
            color: var(--success-color);
        }

        .trend-down {
            color: var(--danger-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        /* Uniformiser les cartes du haut */
        .stats-card {
            background: linear-gradient(135deg, #4361ee, #3a55dd);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Uniformiser le style des graphiques */
        .card {
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.07);
            border-radius: 10px;
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: rgb(78, 96, 177);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 1rem 1.5rem;
        }

        .card-title {
            margin-bottom: 0;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }

            .chart-container {
                margin-bottom: 1rem;
            }

            .chart-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/admin_navbar.php'; ?>

    <div class="container mt-4">
        <!-- Cartes de statistiques -->
        <div class="stats-grid fade-in">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-value">
                    <?php echo $userStats['total']; ?>
                    <span class="trend-indicator trend-up">
                        <i class="fas fa-arrow-up"></i> <?php echo $userStats['growth']; ?>%
                    </span>
                </div>
                <div class="stats-label">Utilisateurs Totaux</div>
            </div>
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stats-value">
                    <?php echo $taskStats['total']; ?>
                    <span class="trend-indicator trend-up">
                        <i class="fas fa-arrow-up"></i> <?php echo $taskStats['growth']; ?>%
                    </span>
                </div>
                <div class="stats-label">Tâches Totales</div>
            </div>
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stats-value">
                    <?php echo $messageStats['total']; ?>
                    <span class="trend-indicator trend-up">
                        <i class="fas fa-arrow-up"></i> <?php echo $messageStats['growth']; ?>%
                    </span>
                </div>
                <div class="stats-label">Messages Totaux</div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="chart-grid fade-in">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-white">Distribution des Tâches par Statut</h5>
                </div>
                <div class="card-body">
                    <div class="chart-actions mb-3">
                        <button class="chart-btn active" data-period="week">Semaine</button>
                        <button class="chart-btn" data-period="month">Mois</button>
                        <button class="chart-btn" data-period="year">Année</button>
                    </div>
                    <canvas id="taskStatusChart"></canvas>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-white">Activité des Utilisateurs</h5>
                </div>
                <div class="card-body">
                    <div class="chart-actions mb-3">
                        <button class="chart-btn active" data-period="week">Semaine</button>
                        <button class="chart-btn" data-period="month">Mois</button>
                        <button class="chart-btn" data-period="year">Année</button>
                    </div>
                    <canvas id="userActivityChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card fade-in mb-4">
            <div class="card-header">
                <h5 class="card-title text-white">Évolution des Messages</h5>
            </div>
            <div class="card-body">
                <div class="chart-actions mb-3">
                    <button class="chart-btn active" data-period="week">Semaine</button>
                    <button class="chart-btn" data-period="month">Mois</button>
                    <button class="chart-btn" data-period="year">Année</button>
                </div>
                <canvas id="messageTrendChart"></canvas>
            </div>
        </div>

        <!-- Statistiques des notifications -->
        <div class="row mt-4 fade-in">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-white">Statistiques des Notifications</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="notificationChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-white">Conseils et Recommandations</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($notificationStats['advice'])): ?>
                            <ul class="list-group list-group-flush">
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
    </div>

    <?php include __DIR__ . '/../includes/admin_footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Animation d'entrée pour les éléments
        document.addEventListener('DOMContentLoaded', function() {
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach((element, index) => {
                setTimeout(() => {
                    element.style.opacity = '1';
                }, index * 100);
            });
            
            // Initialisation des graphiques
            initializeCharts();
        });

        // Fonction pour initialiser les graphiques avec un style uniforme
        function initializeCharts() {
            // Palette de couleurs unifiée
            const chartColors = {
                primary: '#4361ee',
                success: '#4cc9a4',
                warning: '#f9c74f',
                danger: '#f25c54',
                info: '#4895ef',
                backgroundOpacity: 0.1,
                borderOpacity: 1
            };

            // Configuration commune pour les graphiques
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            color: '#555',
                            usePointStyle: true,
                            font: {
                                family: "'Nunito', sans-serif",
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 15,
                        cornerRadius: 8,
                        titleFont: {
                            family: "'Nunito', sans-serif",
                            size: 14
                        },
                        bodyFont: {
                            family: "'Nunito', sans-serif",
                            size: 13
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            };

            // Configuration spécifique pour les graphiques linéaires
            const lineChartOptions = {
                ...commonOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#eee'
                        },
                        ticks: {
                            color: '#555',
                            font: {
                                family: "'Nunito', sans-serif"
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: '#eee'
                        },
                        ticks: {
                            color: '#555',
                            font: {
                                family: "'Nunito', sans-serif"
                            }
                        }
                    }
                }
            };

            // Graphique des statuts de tâches
            const taskStatusChart = new Chart(
                document.getElementById('taskStatusChart').getContext('2d'),
                {
                    type: 'doughnut',
                    data: {
                        labels: ['En cours', 'Terminées', 'En attente', 'Annulées'],
                        datasets: [{
                            data: [
                                <?php echo $taskStats['in_progress']; ?>, 
                                <?php echo $taskStats['completed']; ?>, 
                                <?php echo $taskStats['pending']; ?>, 
                                0
                            ],
                            backgroundColor: [
                                chartColors.primary,
                                chartColors.success,
                                chartColors.warning,
                                chartColors.danger
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        ...commonOptions,
                        cutout: '65%'
                    }
                }
            );

            // Graphique d'activité des utilisateurs
            const userActivityChart = new Chart(
                document.getElementById('userActivityChart').getContext('2d'),
                {
                    type: 'line',
                    data: {
                        labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                        datasets: [{
                            label: 'Utilisateurs actifs',
                            data: generateRandomData(7, 40, 90),
                            borderColor: chartColors.primary,
                            backgroundColor: `rgba(67, 97, 238, ${chartColors.backgroundOpacity})`,
                            pointBackgroundColor: chartColors.primary,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: lineChartOptions
                }
            );

            // Graphique de tendance des messages
            const messageTrendChart = new Chart(
                document.getElementById('messageTrendChart').getContext('2d'),
                {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                        datasets: [{
                            label: 'Messages',
                            data: generateRandomData(6, 100, 300),
                            backgroundColor: chartColors.primary
                        }]
                    },
                    options: {
                        ...lineChartOptions,
                        plugins: {
                            ...lineChartOptions.plugins,
                            legend: {
                                display: false
                            }
                        }
                    }
                }
            );

            // Graphique des notifications
            const notificationChart = new Chart(
                document.getElementById('notificationChart').getContext('2d'),
                {
                    type: 'doughnut',
                    data: {
                        labels: ['Information', 'Avertissement', 'Erreur', 'Succès'],
                        datasets: [{
                            data: [
                                <?php echo isset($notificationStats['stats']['info_count']) ? $notificationStats['stats']['info_count'] : 25; ?>,
                                <?php echo isset($notificationStats['stats']['warning_count']) ? $notificationStats['stats']['warning_count'] : 15; ?>,
                                <?php echo isset($notificationStats['stats']['error_count']) ? $notificationStats['stats']['error_count'] : 8; ?>,
                                <?php echo isset($notificationStats['stats']['success_count']) ? $notificationStats['stats']['success_count'] : 42; ?>
                            ],
                            backgroundColor: [
                                chartColors.info,
                                chartColors.warning,
                                chartColors.danger,
                                chartColors.success
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        ...commonOptions,
                        cutout: '65%'
                    }
                }
            );
        }

        // Fonction pour générer des données aléatoires pour les démos
        function generateRandomData(count, min, max) {
            return Array.from({length: count}, () => Math.floor(Math.random() * (max - min + 1)) + min);
        }

        // Gestion des boutons de période pour tous les graphiques
        $('.chart-btn').click(function() {
            const $container = $(this).closest('.card-body, .chart-container');
            $container.find('.chart-btn').removeClass('active');
            $(this).addClass('active');
            
            // Simuler un changement de données basé sur la période
            const period = $(this).data('period');
            const chartId = $container.find('canvas').attr('id');
            
            // Dans un cas réel, vous feriez une requête AJAX ici pour obtenir de nouvelles données
            // Pour la démonstration, nous allons simplement mettre à jour avec des données aléatoires
            updateChartData(chartId, period);
        });

        // Fonction pour mettre à jour les données du graphique en fonction de la période
        function updateChartData(chartId, period) {
            const chart = Chart.getChart(chartId);
            if (!chart) return;
            
            // Générer de nouvelles données selon la période et le type de graphique
            if (chart.config.type === 'line' || chart.config.type === 'bar') {
                let labels = [];
                let dataCount = 7; // Par défaut pour semaine
                
                switch(period) {
                    case 'week':
                        labels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                        dataCount = 7;
                        break;
                    case 'month':
                        labels = Array.from({length: 30}, (_, i) => (i + 1).toString());
                        dataCount = 30;
                        break;
                    case 'year':
                        labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
                        dataCount = 12;
                        break;
                }
                
                chart.data.labels = labels;
                chart.data.datasets.forEach(dataset => {
                    dataset.data = generateRandomData(dataCount, 30, 100);
                });
            }
            
            chart.update();
        }
    </script>
</body>
</html>