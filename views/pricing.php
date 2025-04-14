<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Temporary version without Stripe integration
$hasSubscription = isset($_SESSION['subscription_status']) && $_SESSION['subscription_status'] === 'active';
?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/pfe/assets/images/gdt.png" type="image/x-icon">
    <title>TaskFlow - Abonnements</title>
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/dashboard.css">
    <style>
        .pricing-card {
            position: relative;
            transition: transform 0.3s;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .pricing-card:hover {
            transform: translateY(-5px);
        }
        .pricing-header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .pricing-body {
            padding: 2rem;
            background: white;
        }
        .price {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 1rem 0;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .btn-upgrade {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none;
            width: 100%;
            padding: 0.75rem;
            font-weight: bold;
        }
        .recommended-badge {
            position: absolute;
            top: 34px;
            right: 0px;
            background: #FFD700;
            color: white;
            padding: 5px 15px;
            border-radius: 0 10px 0 10px;
            font-weight: bold;
            transform: translateY(-100%);
        }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <main class="container py-5 " style="margin-top: 70px;">
        <h2 class="text-center mb-5"  style="font-family: 'Courier New', Courier, monospace;">Nos Offres d'Abonnement</h2>
        
        <div class="row">
            <!-- Free Plan -->
            <div class="col-md-4 mb-4">
                <div class="pricing-card h-100">
                    <div class="pricing-header">
                        <h3>Gratuit</h3>
                    </div>
                    <div class="pricing-body">
                        <div class="price">0 DH/mois</div>
                        <ul class="feature-list">
                            <li><i class="fas fa-check text-success me-2"></i> 5 tâches actives</li>
                            <li><i class="fas fa-check text-success me-2"></i> Collaboration basique</li>
                            <li><i class="fas fa-check text-success me-2"></i> Support par email</li>
                            <li><i class="fas fa-times text-danger me-2"></i> Statistiques avancées</li>
                            <li><i class="fas fa-times text-danger me-2"></i> Priorité support</li>
                        </ul>
                        <button class="btn btn-outline-primary mt-3" disabled>Votre plan actuel</button>
                    </div>
                </div>
            </div>

            <!-- Premium Plan -->
            <div class="col-md-4 mb-4">
                <div class="pricing-card h-100">
                    <div class="recommended-badge">Recommandé</div>
                    <div class="pricing-header">
                        <h3>Premium</h3>
                    </div>
                    <div class="pricing-body">
                        <div class="price">99 DH/mois</div>
                        <ul class="feature-list">
                            <li><i class="fas fa-check text-success me-2"></i> Tâches illimitées</li>
                            <li><i class="fas fa-check text-success me-2"></i> Collaboration complète</li>
                            <li><i class="fas fa-check text-success me-2"></i> Support prioritaire</li>
                            <li><i class="fas fa-check text-success me-2"></i> Statistiques avancées</li>
                            <li><i class="fas fa-check text-success me-2"></i> Export des données</li>
                        </ul>
                        <?php if ($hasSubscription): ?>
                            <button class="btn btn-success mt-3" disabled>Abonnement actif</button>
                        <?php else: ?>
                            <button class="btn btn-upgrade text-white mt-3" onclick="window.location.href='/pfe/views/subscription/success.php?plan=premium'">Choisir ce plan</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Business Plan -->
            <div class="col-md-4 mb-4">
                <div class="pricing-card h-100">
                    <div class="pricing-header">
                        <h3>Business</h3>
                    </div>
                    <div class="pricing-body">
                        <div class="price">199 DH/mois</div>
                        <ul class="feature-list">
                            <li><i class="fas fa-check text-success me-2"></i> Tous les avantages Premium</li>
                            <li><i class="fas fa-check text-success me-2"></i> Gestion d'équipe</li>
                            <li><i class="fas fa-check text-success me-2"></i> Support 24/7</li>
                            <li><i class="fas fa-check text-success me-2"></i> API d'intégration</li>
                            <li><i class="fas fa-check text-success me-2"></i> Formation personnalisée</li>
                        </ul>
                        <?php if ($hasSubscription): ?>
                            <button class="btn btn-success mt-3" disabled>Abonnement actif</button>
                        <?php else: ?>
                            <button class="btn btn-upgrade text-white mt-3" onclick="window.location.href='/pfe/views/subscription/success.php?plan=business'">Choisir ce plan</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
                <p>&copy; 2021 - <?php echo date('Y '); ?> TaskFlow. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/pfe/assets/js/main.js"></script>
    
</body>
</html>
