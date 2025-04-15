<?php
session_start();
require_once "config/database.php";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/pfe/assets/images/gdt.png" type="image/x-icon">
    <title>TaskFlow - Gestion de Tâches Intelligente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 animate-fadeInUp">
                    <h1>Gérez vos tâches efficacement avec TaskFlow</h1>
                    <p>TaskFlow est une solution de gestion des tâches qui vous permet de structurer vos projets de manière organisée et efficace. Grâce à son interface intuitive, vous pouvez facilement suivre l'avancement de vos tâches, collaborer avec votre équipe, et visualiser vos progrès en temps réel. Que vous travailliez sur un projet personnel ou professionnel, TaskFlow vous aide à rester concentré et à atteindre vos objectifs plus rapidement.</p>
                   <div class="alert">
                   <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="d-flex gap-3">
                            <div class="d-flex gap-3">
                            <a href="/pfe/views/auth/register.php" class="btn btn-light btn-lg">Commencer gratuitement </a>
                            <a href="/pfe/views/auth/login.php" class="btn btn-outline-light btn-lg">Se connecter</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/pfe/views/dashboard.php" class="btn btn-light btn-lg">Accéder au tableau de bord</a>
                    <?php endif; ?>
                   </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="/pfe/assets/images/taches.png" alt="TaskFlow Illustration" class="img-fluid" >
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon purple">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h3>Organisation des taches</h3>
                        <p>Visualisez et gérez vos tâches avec une interface Kanban intuitive et flexible.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon blue">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Suivi des Progrès</h3>
                        <p>Suivez votre progression et visualisez vos performances en temps réel.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon green">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3>Notifications</h3>
                        <p>Restez informé des échéances et des mises à jour importantes de vos tâches.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon pink">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Statut Utilisateur</h3>
                        <p>Indiquez votre disponibilité et gérez votre charge de travail efficacement.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta">
        <div class="container">
            <h2>Prêt à améliorer votre productivité ?</h2>
            <p>Rejoignez des milliers d'utilisateurs qui font confiance à TaskFlow pour gérer leurs tâches quotidiennes.
            </p>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="/pfe/views/auth/register.php" class="btn btn-light btn-lg">Commencer maintenant</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
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

                        <li><a href="FAQ.php">FAQ</a></li>
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
                <p>&copy; 2021 - <?php echo date('Y '); ?> <strong>TaskFlow</strong>. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>