<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /pfe/views/auth/login.php');
    exit();
}

require_once "../controllers/TaskController.php";
$taskController = new TaskController();
$statsResult = $taskController->getTaskStats();

$stats = $statsResult['success'] ? $statsResult['data'] : [];

$totalTasks = $stats['total'] ?? 0;
$inProgressTasks = $stats['in_progress'] ?? 0;
$completedTasks = $stats['completed'] ?? 0;
?>

<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - TaskManager</title>
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <link rel="stylesheet" href="/pfe/assets/css/dashboard.css">
    <style>
           .chart-container {
            width: 80%;
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        canvas {
            max-width: 100%;
            height: auto;
        }

        [data-theme="dark"] {
            background-color: #121212;
            color: #ffffff; 
        }

        [data-theme="dark"] .chart-container {
            background: #1e1e1e; 
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.1);
        }

        [data-theme="dark"] footer {
            background: #1e1e1e;
            color: #ffffff; 
        }

        [data-theme="dark"] .footer-links a {
            color: #ffffff; 
        }
    </style>
</head>

<body>

<?php include "../includes/navbar.php"; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Statistiques des tâches</h2>

    <div class="chart-container">
        <canvas id="taskChart"></canvas>
    </div>
</div>

  <!-- Footer -->
  <footer style="margin-top: 60px;">

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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('taskChart').getContext('2d');

        // Création du gradient pour un effet moderne
        const gradientTotal = ctx.createLinearGradient(0, 0, 0, 400);
        gradientTotal.addColorStop(0, "rgba(0, 123, 255, 0.5)");
        gradientTotal.addColorStop(1, "rgba(0, 123, 255, 0)");

        const gradientInProgress = ctx.createLinearGradient(0, 0, 0, 400);
        gradientInProgress.addColorStop(0, "rgba(255, 193, 7, 0.5)");
        gradientInProgress.addColorStop(1, "rgba(255, 193, 7, 0)");

        const gradientCompleted = ctx.createLinearGradient(0, 0, 0, 400);
        gradientCompleted.addColorStop(0, "rgba(40, 167, 69, 0.5)");
        gradientCompleted.addColorStop(1, "rgba(40, 167, 69, 0)");

        const taskChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Total", "En cours", "Terminées"],
                datasets: [{
                    label: "Tâches Totales",
                    data: [<?php echo $totalTasks; ?>, null, null],
                    borderColor: "#007bff",
                    backgroundColor: gradientTotal,
                    borderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 10,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: "Tâches en cours",
                    data: [null, <?php echo $inProgressTasks; ?>, null],
                    borderColor: "#ffc107",
                    backgroundColor: gradientInProgress,
                    borderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 10,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: "Tâches Terminées",
                    data: [null, null, <?php echo $completedTasks; ?>],
                    borderColor: "#28a745",
                    backgroundColor: gradientCompleted,
                    borderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 10,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            },
                            color: '#333'
                        }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: "rgba(0,0,0,0.7)",
                        titleFont: { size: 14 },
                        bodyFont: { size: 14 },
                        padding: 10,
                        cornerRadius: 5,
                        callbacks: {
                            label: function (tooltipItem) {
                                return `${tooltipItem.dataset.label}: ${tooltipItem.raw} tâches`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 12 },
                            color: "#555"
                        },
                        grid: {
                            color: "rgba(200, 200, 200, 0.3)"
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 12 },
                            color: "#555"
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</body>
</html>




