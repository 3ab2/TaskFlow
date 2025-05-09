<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/pfe/assets/images/gdt.png" type="image/x-icon">
    
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
    <section class="hero text-center text-white py-5" style="background: linear-gradient(135deg, rgba(72, 38, 174, 0.82),rgb(115, 97, 174));">
        <div class="container">
            <h1 class="display-4 " style="margin-top: 130px;">Optimisez votre productivité avec TaskFlow</h1>
            <p class="lead">Gérez vos tâches efficacement en les organisant par projet et en définissant des échéances. Collaborez en toute simplicité en partageant vos tâches avec vos collègues et en suivant leur progression en temps réel.</p>
            <div class="alert" data-aos="fade-up" data-aos-delay="300">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="/pfe/views/auth/register.php" class="btn btn-light btn-lg mt-3">Commencer maintenant</a>
                <?php else: ?>
                    <a href="/pfe/views/statistiques.php" class="btn btn-light btn-lg mt-3">Voir vos statistiques</a>
                    <a href="/pfe/views/dashboard.php" class="btn btn-light btn-lg mt-3">Aller au Tableau de bord</a>
                <?php endif; ?>
            </div>
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

    <!-- Why Choose Us Section -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Pourquoi nous choisir ?</h2>
        <div class="row justify-content-center">
            <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card p-4 text-center shadow-sm h-100">
                    <i class="fas fa-dollar-sign fa-3x text-primary mb-3"></i>
                    <h5>100% gratuit ou tarif abordable</h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card p-4 text-center shadow-sm h-100">
                    <i class="fas fa-tachometer-alt fa-3x text-success mb-3"></i>
                    <h5>Interface simple et intuitive</h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card p-4 text-center shadow-sm h-100">
                    <i class="fas fa-headset fa-3x text-danger mb-3"></i>
                    <h5>Support technique réactif</h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="card p-4 text-center shadow-sm h-100">
                    <i class="fas fa-shield-alt fa-3x text-warning mb-3"></i>
                    <h5>Plateforme sécurisée</h5>
                </div>
            </div>
        </div>
    </section>

    <!-- User Testimonials Section -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Témoignages d'utilisateurs</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card p-3 shadow-sm text-center">
                    <img src="/pfe/assets/images/profile.png" alt="Utilisateur 1" class="rounded-circle mx-auto d-block" style="width: 80px; height: 80px; object-fit: cover;">
                    <h5 class="mt-3">Samir Laafi</h5>
                    <p class="fst-italic">"TaskFlow m’a permis de mieux organiser mon travail quotidien."</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card p-3 shadow-sm text-center">
                    <img src="/pfe/assets/images/profile_12.jpg" alt="Utilisateur 2" class="rounded-circle mx-auto d-block" style="width: 80px; height: 80px; object-fit: cover;">
                    <h5 class="mt-3">3AB2U </h5>
                    <p class="fst-italic">"Une application intuitive qui a transformé ma gestion de tâches."</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card p-3 shadow-sm text-center">
                    <img src="/pfe/uploads/profile/680c0e57047ec.jpeg" alt="Utilisateur 3" class="rounded-circle mx-auto d-block" style="width: 80px; height: 80px; object-fit: cover;">
                    <h5 class="mt-3">Ahmed Benali</h5>
                    <p class="fst-italic">"Grâce à TaskFlow, je collabore mieux avec mon équipe."</p>
                </div>
            </div>
        </div>
    </section>

  

    <!-- Interface Preview Section -->
    <section class="container-fluid my-5">
        <h2 class="text-center mb-4">Aperçu de l’interface</h2>
        <div class="row justify-content-center">
            <div class="col-10 text-center">
                
                <!-- Optional video demo -->
            
                <video width="100%" autoplay loop muted class="mt-3 rounded shadow-sm" data-aos="fade-up">
                    <source src="/pfe/assets/images/demo.mp4" type="video/mp4">
                    Votre navigateur ne supporte pas la lecture de vidéos.
                </video>
                
            </div>
        </div>
    </section>



      <!-- Security and Privacy Section -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Sécurité et Confidentialité</h2>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card p-5 shadow-sm">
                    <h4 class="mb-3"><i class="fas fa-lock text-primary me-2"></i>Authentification Sécurisée</h4>
                    <p>Notre plateforme utilise une authentification sécurisée pour garantir que vos informations restent protégées. Nous employons des protocoles de sécurité avancés pour prévenir tout accès non autorisé.</p>
                </div>
                <div class="card p-5 shadow-sm mt-4">
                    <h4 class="mb-3"><i class="fas fa-user-shield text-primary me-2"></i>Respect de la Vie Privée</h4>
                    <p>Nous nous engageons à respecter votre vie privée. Vos données personnelles ne seront jamais partagées sans votre consentement explicite, et nous adhérons aux normes les plus strictes de protection des données.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Multi-Device Compatibility Section -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Compatibilité multi-supports</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 col-sm-6 col-12 p-3">
                <div class="d-flex flex-column align-items-center">
                    <i class="fas fa-desktop text-primary" title="Ordinateur" style="font-size: 5rem;"></i>
                    <p class="fs-1 mt-2">Ordinateur</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12 p-3">
                <div class="d-flex flex-column align-items-center">
                    <i class="fas fa-tablet-alt text-primary" title="Tablette" style="font-size: 5rem;"></i>
                    <p class="fs-1 mt-2">Tablette</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12 p-3">
                <div class="d-flex flex-column align-items-center">
                    <i class="fas fa-mobile-alt text-primary" title="Mobile" style="font-size: 5rem;"></i>
                    <p class="fs-1 mt-2">Mobile</p>
                </div>
            </div>
        </div>
    </section>

   
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
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
