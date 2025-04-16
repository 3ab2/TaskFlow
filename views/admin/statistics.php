<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

$admin = new AdminController();
if (!$admin->isAdmin()) {
    header('Location: /pfe/views/auth/login.php');
    exit();
}

// Récupérer les statistiques
$users = $admin->getAllUsers();
$tasks = $admin->getAllTasks();
$messages = $admin->getAllMessages();
$notifications = $admin->getAllNotifications();

// Calculer les statistiques des utilisateurs
$userStats = [
    'total' => count($users),
    'active' => count(array_filter($users, fn($user) => isset($user['active']) && $user['active'] == 1)),
    'admins' => count(array_filter($users, fn($user) => isset($user['role']) && $user['role'] == 'admin')),
    'growth' => 15 // Peut être calculé en comparant avec les données historiques
];

// Calculer les statistiques des tâches
$taskStats = [
    'total' => count($tasks),
    'in_progress' => count(array_filter($tasks, fn($task) => $task['status'] == 'in_progress')),
    'completed' => count(array_filter($tasks, fn($task) => $task['status'] == 'completed')),
    'to_do' => count(array_filter($tasks, fn($task) => $task['status'] == 'to_do')),
    'growth' => 8
];

// Calculer les statistiques des messages
$messageStats = [
    'total' => count($messages),
    'read' => count(array_filter($messages, fn($msg) => $msg['is_read'] == 1)),
    'unread' => count(array_filter($messages, fn($msg) => $msg['is_read'] == 0)),
    'growth' => 12
];

// Statistiques des notifications
$notificationStats = [
    'total' => count($notifications),
    'read' => count(array_filter($notifications, fn($notif) => $notif['is_read'] == 1)),
    'unread' => count(array_filter($notifications, fn($notif) => $notif['is_read'] == 0)),
    'types' => [
        'info' => count(array_filter($notifications, fn($notif) => $notif['type'] == 'info')),
        'success' => count(array_filter($notifications, fn($notif) => $notif['type'] == 'success')),
        'warning' => count(array_filter($notifications, fn($notif) => $notif['type'] == 'warning')),
        'error' => count(array_filter($notifications, fn($notif) => $notif['type'] == 'error'))
    ]
];

// Préparer les données pour les graphiques
$taskStatusData = [
    'labels' => ['À faire', 'En cours', 'Terminées'],
    'data' => [$taskStats['to_do'], $taskStats['in_progress'], $taskStats['completed']],
    'colors' => ['#FF6384', '#36A2EB', '#4BC0C0']
];

$userActivityData = [
    'labels' => ['Actifs', 'Inactifs', 'Admins'],
    'data' => [$userStats['active'], $userStats['total'] - $userStats['active'], $userStats['admins']],
    'colors' => ['#4BC0C0', '#FF6384', '#FFCE56']
];

$messageTrendData = [
    'labels' => ['Lus', 'Non lus'],
    'data' => [$messageStats['read'], $messageStats['unread']],
    'colors' => ['#36A2EB', '#FF6384']
];

$notificationTypeData = [
    'labels' => ['Info', 'Succès', 'Avertissement', 'Erreur'],
    'data' => [
        $notificationStats['types']['info'],
        $notificationStats['types']['success'],
        $notificationStats['types']['warning'],
        $notificationStats['types']['error']
    ],
    'colors' => ['#36A2EB', '#4BC0C0', '#FFCE56', '#FF6384']
];
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

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }

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
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="stats-value">
                    <?php echo $notificationStats['total']; ?>
                    <span class="trend-indicator trend-up">
                        <i class="fas fa-arrow-up"></i> 5%
                    </span>
                </div>
                <div class="stats-label">Notifications</div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="row mt-4 fade-in">
            <!-- Distribution des Tâches par Statut -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">Distribution des Tâches par Statut</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="taskStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activité des Utilisateurs -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">Activité des Utilisateurs</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="userActivityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Évolution des Messages -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">Statut des Messages</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="messageTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques des Notifications -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">Types de Notifications</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="notificationTypeChart"></canvas>
                        </div>
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

            // Graphique de distribution des tâches par statut
            const taskStatusCtx = document.getElementById('taskStatusChart').getContext('2d');
            new Chart(taskStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($taskStatusData['labels']); ?>,
                    datasets: [{
                        data: <?php echo json_encode($taskStatusData['data']); ?>,
                        backgroundColor: <?php echo json_encode($taskStatusData['colors']); ?>,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
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
                    }
                }
            });

            // Graphique d'activité des utilisateurs
            const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
            new Chart(userActivityCtx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($userActivityData['labels']); ?>,
                    datasets: [{
                        data: <?php echo json_encode($userActivityData['data']); ?>,
                        backgroundColor: <?php echo json_encode($userActivityData['colors']); ?>,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });

            // Graphique d'évolution des messages
            const messageTrendCtx = document.getElementById('messageTrendChart').getContext('2d');
            new Chart(messageTrendCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($messageTrendData['labels']); ?>,
                    datasets: [{
                        label: 'Messages',
                        data: <?php echo json_encode($messageTrendData['data']); ?>,
                        backgroundColor: <?php echo json_encode($messageTrendData['colors']); ?>,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Graphique des types de notifications
            const notificationTypeCtx = document.getElementById('notificationTypeChart').getContext('2d');
            new Chart(notificationTypeCtx, {
                type: 'polarArea',
                data: {
                    labels: <?php echo json_encode($notificationTypeData['labels']); ?>,
                    datasets: [{
                        data: <?php echo json_encode($notificationTypeData['data']); ?>,
                        backgroundColor: <?php echo json_encode($notificationTypeData['colors']); ?>,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>