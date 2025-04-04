<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

$admin = new AdminController();
if (!$admin->isAdmin()) {
    header('Location: /pfe/views/auth/login.php');
    exit();
}

// Récupérer les statistiques
$userStats = $admin->getUserStatistics();
$taskStats = $admin->getTaskStatistics();
$messageStats = $admin->getMessageStatistics();

// Récupérer les statistiques des notifications
$notificationStats = $admin->getNotificationStatistics();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskflow Admin - Statistiques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <?php include '../includes/admin_style.php'; ?>
    <style>
        body {
            background-color: var(--primary-bg);
            color: var(--text-color);
        }

        .stats-card {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border-radius: 15px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
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
            background: var(--card-bg);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px var(--shadow);
            border: 1px solid var(--border-color);
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
            color: var(--text-color);
        }

        .chart-actions {
            display: flex;
            gap: 0.5rem;
        }

        .chart-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            background: var(--secondary-bg);
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .chart-btn:hover {
            background: var(--primary-color);
            color: white;
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
            color: #10b981;
        }

        .trend-down {
            color: #ef4444;
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
        <div class="stats-grid">
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
        <div class="chart-grid">
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title">Distribution des Tâches par Statut</h5>
                    <div class="chart-actions">
                        <button class="chart-btn active" data-period="week">Semaine</button>
                        <button class="chart-btn" data-period="month">Mois</button>
                        <button class="chart-btn" data-period="year">Année</button>
                    </div>
                </div>
                <canvas id="taskStatusChart"></canvas>
            </div>
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title">Activité des Utilisateurs</h5>
                    <div class="chart-actions">
                        <button class="chart-btn active" data-period="week">Semaine</button>
                        <button class="chart-btn" data-period="month">Mois</button>
                        <button class="chart-btn" data-period="year">Année</button>
                    </div>
                </div>
                <canvas id="userActivityChart"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <h5 class="chart-title">Évolution des Messages</h5>
                <div class="chart-actions">
                    <button class="chart-btn active" data-period="week">Semaine</button>
                    <button class="chart-btn" data-period="month">Mois</button>
                    <button class="chart-btn" data-period="year">Année</button>
                </div>
            </div>
            <canvas id="messageTrendChart"></canvas>
        </div>

        <!-- Nouveau graphique pour les notifications -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Statistiques des Notifications</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="notificationChart"></canvas>
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
    </div>

    <?php include __DIR__ . '/../includes/admin_footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuration des graphiques
        const chartConfigs = {
            taskStatusChart: {
                type: 'doughnut',
                data: {
                    labels: ['En cours', 'Terminées', 'En attente', 'Annulées'],
                    datasets: [{
                        data: [<?php echo $taskStats['in_progress']; ?>, 
                               <?php echo $taskStats['completed']; ?>, 
                               <?php echo $taskStats['pending']; ?>, 
                               0],
                        backgroundColor: [
                            '#4f46e5',
                            '#10b981',
                            '#f59e0b',
                            '#ef4444'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: 'var(--text-color)'
                            }
                        }
                    }
                }
            },
            userActivityChart: {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                    datasets: [{
                        label: 'Utilisateurs actifs',
                        data: [65, 59, 80, 81, 56, 55, 40],
                        borderColor: '#4f46e5',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(79, 70, 229, 0.1)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'var(--border-color)'
                            },
                            ticks: {
                                color: 'var(--text-color)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'var(--border-color)'
                            },
                            ticks: {
                                color: 'var(--text-color)'
                            }
                        }
                    }
                }
            },
            messageTrendChart: {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                    datasets: [{
                        label: 'Messages',
                        data: [120, 150, 180, 200, 220, 250],
                        backgroundColor: '#4f46e5'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'var(--border-color)'
                            },
                            ticks: {
                                color: 'var(--text-color)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'var(--border-color)'
                            },
                            ticks: {
                                color: 'var(--text-color)'
                            }
                        }
                    }
                }
            }
        };

        // Création des graphiques
        const charts = {};
        Object.keys(chartConfigs).forEach(chartId => {
            const ctx = document.getElementById(chartId).getContext('2d');
            charts[chartId] = new Chart(ctx, chartConfigs[chartId]);
        });

        // Gestion des boutons de période
        $('.chart-btn').click(function() {
            const $container = $(this).closest('.chart-container');
            $container.find('.chart-btn').removeClass('active');
            $(this).addClass('active');
            
            // Ici, vous pouvez ajouter la logique pour mettre à jour les données
            // en fonction de la période sélectionnée
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
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Répartition des Notifications'
                    }
                }
            }
        });
    </script>
</body>
</html> 