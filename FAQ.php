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
    <title>FAQ - TaskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <style>
        .accordion-button {
            transition: all 0.3s ease;
        }
        .accordion-button:hover {
            transform: translateX(5px);
        }
        .accordion-button:not(.collapsed) {
            background-color: #f8f9fa;
        }
        .accordion-body {
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .faq-icon {
            margin-right: 10px;
            color: #0d6efd;
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <section class="faq container py-5 mt-5">
        <h2 class="text-center mb-4">Foire Aux Questions (FAQ)</h2>
        <div class="accordion" id="faqAccordion">
            <!-- Existing Account Questions -->
            <h3 class="mt-4 mb-3">Gestion de compte</h3>
            <div class="accordion-item">
                <h2 class="accordion-header" id="question1">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#answer1">
                        <i class="fas fa-user-plus faq-icon animate__animated animate__fadeInLeft"></i> Comment créer un compte sur TaskFlow ?
                    </button>
                </h2>
                <div id="answer1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Pour créer un compte, cliquez sur "Commencer gratuitement", puis remplissez le formulaire
                        d'inscription.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#answer2">
                        <i class="fas fa-euro-sign faq-icon animate__animated animate__fadeInLeft"></i> Est-ce que TaskFlow est gratuit ?
                    </button>
                </h2>
                <div id="answer2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Oui, TaskFlow propose une version gratuite avec des fonctionnalités de base. Des options premium
                        peuvent être disponibles.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question3">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#answer3">
                        <i class="fas fa-key faq-icon animate__animated animate__fadeInLeft"></i> Comment puis-je réinitialiser mon mot de passe ?
                    </button>
                </h2>
                <div id="answer3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Vous pouvez réinitialiser votre mot de passe en cliquant sur "Mot de passe oublié" sur la page
                        de connexion.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question4">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#answer4">
                        <i class="fas fa-headset faq-icon animate__animated animate__fadeInLeft"></i> Comment contacter le support client ?
                    </button>
                </h2>
                <div id="answer4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Vous pouvez nous contacter par email à <strong>support@taskflow.com</strong> ou via notre
                        formulaire de contact.
                    </div>
                </div>
            </div>

            <!-- Task Operations (15 questions) -->
            <h3 class="mt-4 mb-3">Gestion des tâches</h3>
            <div class="accordion-item">
                <h2 class="accordion-header" id="question5">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer5">
                        <i class="fas fa-plus-circle faq-icon animate__animated animate__fadeInLeft"></i> Comment créer une nouvelle tâche ?
                    </button>
                </h2>
                <div id="answer5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Cliquez sur le bouton "+ Nouvelle tâche" dans votre tableau de bord, remplissez les détails et cliquez sur "Enregistrer".
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question6">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer6">
                        <i class="fas fa-users faq-icon animate__animated animate__fadeInLeft"></i> Puis-je assigner une tâche à plusieurs personnes ?
                    </button>
                </h2>
                <div id="answer6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Oui, vous pouvez sélectionner plusieurs membres d'équipe lors de la création ou modification d'une tâche.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question12">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer12">
                        <i class="fas fa-edit faq-icon animate__animated animate__fadeInLeft"></i> Comment modifier une tâche existante ?
                    </button>
                </h2>
                <div id="answer12" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Cliquez sur la tâche dans votre liste, apportez vos modifications et cliquez sur "Enregistrer".
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question13">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer13">
                        <i class="fas fa-paperclip faq-icon animate__animated animate__fadeInLeft"></i> Puis-je ajouter des pièces jointes à une tâche ?
                    </button>
                </h2>
                <div id="answer13" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Oui, vous pouvez ajouter des fichiers lors de la création ou modification d'une tâche en utilisant le bouton "Ajouter des fichiers".
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question14">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer14">
                        <i class="fas fa-archive faq-icon animate__animated animate__fadeInLeft"></i> Comment archiver une tâche terminée ?
                    </button>
                </h2>
                <div id="answer14" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Marquez la tâche comme terminée, puis cliquez sur l'option "Archiver" dans le menu de la tâche.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question15">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer15">
                        <i class="fas fa-filter faq-icon animate__animated animate__fadeInLeft"></i> Comment filtrer les tâches par statut ?
                    </button>
                </h2>
                <div id="answer15" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Utilisez le menu déroulant "Filtrer" dans votre tableau de bord et sélectionnez le statut souhaité.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question16">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer16">
                        <i class="fas fa-bell faq-icon animate__animated animate__fadeInLeft"></i> Puis-je définir des rappels pour les tâches ?
                    </button>
                </h2>
                <div id="answer16" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Oui, lors de la création ou modification d'une tâche, vous pouvez définir des rappels dans la section "Notifications".
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question17">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer17">
                        <i class="fas fa-share-alt faq-icon animate__animated animate__fadeInLeft"></i> Comment partager une tâche avec quelqu'un qui n'est pas dans mon équipe ?
                    </button>
                </h2>
                <div id="answer17" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Vous pouvez générer un lien de partage depuis le menu de la tâche et l'envoyer à la personne concernée.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="question18">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer18">
                        <i class="fas fa-redo faq-icon animate__animated animate__fadeInLeft"></i> Comment créer une tâche récurrente ?
                    </button>
                </h2>
                <div id="answer18" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Lors de la création d'une tâche, activez l'option "Récurrente" et définissez la fréquence souhaitée.
                    </div>
                </div>
            </div>

            <!-- Dashboard Features (8 questions) -->
            <h3 class="mt-4 mb-3">Tableau de bord</h3>
            <div class="accordion-item">
                <h2 class="accordion-header" id="question7">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer7">
                        <i class="fas fa-sliders-h faq-icon animate__animated animate__fadeInLeft"></i> Comment personnaliser mon tableau de bord ?
                    </button>
                </h2>
                <div id="answer7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Cliquez sur l'icône "Personnaliser" en haut à droite du tableau de bord pour modifier l'affichage.
                    </div>
                </div>
            </div>

            <!-- Messaging System (5 questions) -->
            <h3 class="mt-4 mb-3">Messagerie</h3>
            <div class="accordion-item">
                <h2 class="accordion-header" id="question8">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer8">
                        <i class="fas fa-comment-dots faq-icon animate__animated animate__fadeInLeft"></i> Comment envoyer un message à un collègue ?
                    </button>
                </h2>
                <div id="answer8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Allez dans l'onglet "Messages", cliquez sur "Nouveau message", sélectionnez le destinataire et écrivez votre message.
                    </div>
                </div>
            </div>

            <!-- Notifications (5 questions) -->
            <h3 class="mt-4 mb-3">Notifications</h3>
            <div class="accordion-item">
                <h2 class="accordion-header" id="question9">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer9">
                        <i class="fas fa-bell-slash faq-icon animate__animated animate__fadeInLeft"></i> Comment désactiver les notifications ?
                    </button>
                </h2>
                <div id="answer9" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Allez dans "Paramètres" > "Notifications" et ajustez vos préférences.
                    </div>
                </div>
            </div>

            <!-- Profile & Settings (5 questions) -->
            <h3 class="mt-4 mb-3">Profil & Paramètres</h3>
            <div class="accordion-item">
                <h2 class="accordion-header" id="question10">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer10">
                        <i class="fas fa-user-circle faq-icon animate__animated animate__fadeInLeft"></i> Comment changer ma photo de profil ?
                    </button>
                </h2>
                <div id="answer10" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Allez dans "Profil" > "Modifier le profil" et cliquez sur votre photo actuelle pour la changer.
                    </div>
                </div>
            </div>

            <!-- Troubleshooting (5 questions) -->
            <h3 class="mt-4 mb-3">Dépannage</h3>
            <div class="accordion-item">
                <h2 class="accordion-header" id="question11">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answer11">
                        <i class="fas fa-envelope faq-icon animate__animated animate__fadeInLeft"></i> Que faire si je ne reçois pas d'email de confirmation ?
                    </button>
                </h2>
                <div id="answer11" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Vérifiez votre dossier spam. Si vous ne trouvez pas l'email, cliquez sur "Renvoyer l'email" ou contactez le support.
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