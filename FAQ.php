<?php
session_start();
require_once "config/database.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - TaskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <section class="faq container py-5">
        <h1 class="text-center mb-4">Foire Aux Questions (FAQ)</h1>
        <div class="accordion" id="faqAccordion">
            
            <div class="accordion-item">
                <h2 class="accordion-header" id="question1">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#answer1">
                        Comment créer un compte sur TaskFlow ?
                    </button>
                </h2>
                <div id="answer1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Pour créer un compte, cliquez sur "Commencer gratuitement", puis remplissez le formulaire d'inscription.
                    </div>
                </div>
            </div>
            
            <div class="accordion-item">
                <h2 class="accordion-header" id="question2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer2">
                        Est-ce que TaskFlow est gratuit ?
                    </button>
                </h2>
                <div id="answer2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Oui, TaskFlow propose une version gratuite avec des fonctionnalités de base. Des options premium peuvent être disponibles.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question3">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer3">
                        Comment puis-je réinitialiser mon mot de passe ?
                    </button>
                </h2>
                <div id="answer3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Vous pouvez réinitialiser votre mot de passe en cliquant sur "Mot de passe oublié" sur la page de connexion.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question4">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer4">
                        Comment contacter le support client ?
                    </button>
                </h2>
                <div id="answer4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Vous pouvez nous contacter par email à <strong>support@taskflow.com</strong> ou via notre formulaire de contact.
                    </div>
                </div>
            </div>
        </div>
    </section>

     <!-- Footer -->
     <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-section">
                    <h5>À propos de TaskFlow</h5>
                    <p>TaskFlow est une solution moderne de gestion de tâches conçue pour optimiser votre productivité et simplifier votre organisation quotidienne.</p>
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
                <p>&copy; <?php echo date('Y '); ?> TaskFlow. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
