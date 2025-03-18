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

$total = $stats['total'] ?? 0;
$in_progress = $stats['in_progress'] ?? 0;
$completed = $stats['completed'] ?? 0;
$overdue = $stats['overdue'] ?? 0;

$showEqualImage = ($total == $completed && $total > 0);
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

    <div class="container py-4 " style="margin-top: 10px;">
        <h2 class="text-center mb-4"> Statistiques des Tâches</h2>

        <!-- Explication de la lecture du diagramme -->
        <div class="alert alert-info" role="alert">
            <h5>Comment lire ce diagramme ?</h5>
            <p>Ce diagramme représente l'évolution de vos tâches :</p>
            <ul>
                <li><strong>Total :</strong> Nombre total de tâches créées.</li>
                <li><strong>En cours :</strong> Tâches en cours de traitement.</li>
                <li><strong>Terminées :</strong> Tâches complétées avec succès.</li>
                <li><strong>En retard :</strong> Tâches dont la date limite est dépassée.</li>
            </ul>
        </div>

        <!-- Affichage conditionnel d'une image si total == completed -->
        <?php if ($showEqualImage): ?>
            <div class="text-center my-4">
                <p class="mt-2 text-success"><strong>Félicitations ! 🎉 Toutes vos tâches ont été complétées.</strong></p>
            </div>
        <?php endif; ?>

        <!-- Diagramme -->
        <canvas id="taskChart" width="300" height="130" margin-top="20" ;></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('taskChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Total', 'En cours', 'Terminées', 'En retard'],
                datasets: [{
                    label: 'Nombre de tâches',
                    data: [<?php echo $total; ?>, <?php echo $in_progress; ?>, <?php echo $completed; ?>, <?php echo $overdue; ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: { enabled: true },
                    legend: { display: true }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>


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
    <script src="/pfe/assets/js/main.js"></script>
</body>

</html>
