<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - TaskFlow</title>
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
</head>

<body>
    <?php include "../includes/navbar.php"; ?>

    <!-- Hero Section -->
    <section class="hero text-center text-white py-5 mt-5" style="background: linear-gradient(135deg,rgba(72, 38, 174, 0.82), #2c3e50);">
        <div class="container">
            <h1 class="display-4">Optimisez votre productivité avec TaskFlow</h1>
            <p class="lead">Gérez vos tâches efficacement et collaborez en toute simplicité.</p>
            <a href="/pfe/views/dashboard.php" class="btn btn-light btn-lg mt-3">Commencer maintenant</a>
        </div>
    </section>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Comment utiliser notre site ?</h2>
        <div class="row">
            <div class="col-md-4 mb-5" data-aos="fade-up" data-aos-delay="100" >
                <div class="card p-4 text-center shadow-sm">
                    <i class="fas fa-chart-line fa-3x text-primary"></i>
                    <h4 class="mt-3">Tableau de bord</h4>
                    <p>Suivez vos tâches grâce à un tableau de bord clair et interactif.</p>
                    <a href="/pfe/views/dashboard.php" class="btn btn-primary">Accéder</a>
                </div>
            </div>
            <div class="col-md-4 mb-5" data-aos="fade-up" data-aos-delay="200" >
                <div class="card p-4 text-center shadow-sm">
                    <i class="fas fa-envelope fa-3x text-success"></i>
                    <h4 class="mt-3">Messagerie</h4>
                    <p>Communiquez facilement avec vos collègues en temps réel.</p>
                    <a href="/pfe/views/messages.php" class="btn btn-success">Accéder</a>
                </div>
            </div>
            <div class="col-md-4 mb-5" data-aos="fade-up" data-aos-delay="300">
                <div class="card p-4 text-center shadow-sm">
                    <i class="fas fa-chart-pie fa-3x text-danger"></i>
                    <h4 class="mt-3">Statistiques</h4>
                    <p>Analysez vos performances et suivez l'évolution de vos tâches.</p>
                    <a href="/pfe/views/statistiques.php" class="btn btn-danger">Accéder</a>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Foire Aux Questions</h2>
<div class="container my-4">
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="faqSearch" placeholder="Rechercher une question...">
        <button class="btn btn-outline-secondary" type="button" onclick="searchFAQ()">Rechercher</button>
    </div>
</div>

<script>
    function searchFAQ() {
        const query = document.getElementById('faqSearch').value.toLowerCase();
        const items = document.querySelectorAll('.accordion-item');
        items.forEach(item => {
            const question = item.querySelector('.accordion-button').innerText.toLowerCase();
            if (question.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>

        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">Comment ajouter une tâche ?</button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show">
                    <div class="accordion-body">Allez dans le tableau de bord et cliquez sur "Nouvelle tâche".</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">Comment contacter un utilisateur ?</button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse">
                    <div class="accordion-body">Utilisez la messagerie pour envoyer un message direct.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">Comment modifier une tâche ?</button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse">
                    <div class="accordion-body">Cliquez sur le bouton "Modifier" sur la tâche que vous souhaitez modifier.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">Comment supprimer une tâche ?</button>
                </h2>
                <div id="faq4" class="accordion-collapse collapse">
                    <div class="accordion-body">Cliquez sur le bouton "Supprimer" sur la tâche que vous souhaitez supprimer.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">Comment ajouter un utilisateur ?</button>
                </h2>
                <div id="faq5" class="accordion-collapse collapse">
                    <div class="accordion-body">Allez dans la section "Utilisateurs" et cliquez sur "Nouvel utilisateur".</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">Comment modifier un utilisateur ?</button>
                </h2>
                <div id="faq6" class="accordion-collapse collapse">
                    <div class="accordion-body">Cliquez sur le bouton "Modifier" sur l'utilisateur que vous souhaitez modifier.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">Comment supprimer un utilisateur ?</button>
                </h2>
                <div id="faq7" class="accordion-collapse collapse">
                    <div class="accordion-body">Cliquez sur le bouton "Supprimer" sur l'utilisateur que vous souhaitez supprimer.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">Comment créer un groupe ?</button>
                </h2>
                <div id="faq8" class="accordion-collapse collapse">
                    <div class="accordion-body">Allez dans la section "Groupes" et cliquez sur "Nouveau groupe".</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq9">Comment modifier un groupe ?</button>
                </h2>
                <div id="faq9" class="accordion-collapse collapse">
                    <div class="accordion-body">Cliquez sur le bouton "Modifier" sur le groupe que vous souhaitez modifier.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq10">Comment supprimer un groupe ?</button>
                </h2>
                <div id="faq10" class="accordion-collapse collapse">
                    <div class="accordion-body">Cliquez sur le bouton "Supprimer" sur le groupe que vous souhaitez supprimer.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq11">Comment ajouter un utilisateur à un groupe ?</button>
                </h2>
                <div id="faq11" class="accordion-collapse collapse">
                    <div class="accordion-body">Allez dans la section "Groupes" et cliquez sur le bouton "Ajouter un utilisateur" sur le groupe que vous souhaitez modifier.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq12">Comment modifier un utilisateur dans un groupe ?</button>
                </h2>
                <div id="faq12" class="accordion-collapse collapse">
                    <div class="accordion-body">Cliquez sur le bouton "Modifier" sur l'utilisateur que vous souhaitez modifier dans le groupe.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq13">Comment supprimer un utilisateur d'un groupe ?</button>
                </h2>
                <div id="faq13" class="accordion-collapse collapse">
                    <div class="accordion-body">Cliquez sur le bouton "Supprimer" sur l'utilisateur que vous souhaitez supprimer du groupe.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq14">Comment créer une tâche dans un groupe ?</button>
                </h2>
                <div id="faq14" class="accordion-collapse collapse">
                    <div class="accordion-body">Allez dans la section "Groupes" et cliquez sur le bouton "Nouvelle tâche" sur le groupe que vous souhaitez modifier.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq15">Comment modifier une tâche dans un groupe ?</button>
                </h2>
                <div id="faq15" class="accordion-collapse collapse">
                    <div class="accordion-body">Cliquez sur le bouton "Modifier" sur la tâche que vous souhaitez modifier dans le groupe.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq16">Comment supprimer une tâche dans un groupe ?</button>
                </h2>
                <div id="faq16" class="accordion-collapse collapse">
                    <div class="accordion-body">Cliquez sur le bouton "Supprimer" sur la tâche que vous souhaitez supprimer du groupe.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scroll to Top Button -->
    <button onclick="topFunction()" id="scrollTop" class="btn btn-primary position-fixed" style="bottom: 20px; right: 20px; display: none;">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        // Bouton de retour en haut
        window.onscroll = function() {
            let btn = document.getElementById("scrollTop");
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        };
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
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
                <p>&copy; 2021 - <?php echo date('Y '); ?> TaskFlow. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>

</html>
