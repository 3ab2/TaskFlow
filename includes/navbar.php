<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
    /* Styles existants conservés */
    .navbar {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        padding: 1rem 0;
    }

    /* Nouveaux styles pour le logo et le nom */
    .logo-container {
        position: relative;
        overflow: visible;
        padding: 5px;
        transition: transform 0.3s ease;
    }

    .logo-container:hover {
        transform: scale(1.05);
    }

    .logo-wrapper {
        position: relative;
        margin-right: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-image {
        width: 40px;
        height: 40px;
        object-fit: contain;
        filter: drop-shadow(0 0 3px rgba(255, 255, 255, 0.7));
        z-index: 2;
        animation: pulse-logo 3s infinite ease-in-out;
    }

    .logo-glow {
        position: absolute;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgb(255, 225, 0) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        filter: blur(5px);
        opacity: 0.7;
        z-index: 1;
        animation: glow 3s infinite ease-in-out;
    }

    .brand-text {
        font-weight: 700;
        font-size: 1.4rem;
        letter-spacing: -0.5px;
        position: relative;
        display: flex;
        overflow: hidden;
        z-index: 2;
    }

    .task {
        color: white;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        animation: slide-in 0.5s ease-out;
    }

    .flow {
        background: linear-gradient(90deg, #ffffff, #a5b4fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        position: relative;
        animation: slide-in 0.5s ease-out 0.2s both;
    }

    .flow::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        height: 2px;
        width: 100%;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0), #ffffff, rgba(255, 255, 255, 0));
        animation: slide-glow 2s infinite;
    }

    @keyframes pulse-logo {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    @keyframes glow {

        0%,
        100% {
            opacity: 0.3;
            transform: scale(0.8);
        }

        50% {
            opacity: 0.7;
            transform: scale(1.1);
        }
    }

    @keyframes slide-in {
        0% {
            transform: translateY(20px);
            opacity: 0;
        }

        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slide-glow {

        0%,
        100% {
            transform: translateX(-100%);
        }

        50% {
            transform: translateX(100%);
        }
    }

    /* Animation lors du chargement de la page */
    @keyframes appear {
        0% {
            opacity: 0;
            transform: translateY(-10px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .navbar-brand {
        animation: appear 0.8s ease-out;
    }
    
</style>






<!-- Mettez à jour le style de la navbar.php pour la partie logo et nom -->
<nav class="navbar navbar-expand-lg fixed-top d-inline-block align-top mb-5"
    style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">

    <div class="container">
        <a class="navbar-brand d-flex align-items-center logo-container" href="/pfe/index.php">
            <div class="logo-wrapper">
                <img src="/pfe/assets/images/gdt.png" class="logo-image" alt="Logo de TaskFlow">
                <div class="logo-glow"></div>
            </div>
            <div class="brand-text">
                <span class="task" style="font-family: 'Cascadia Code', sans-serif; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-image: linear-gradient(135deg, #ff7f50 0%,rgb(255, 247, 0) 100%);">Task</span>
                <span class="flow" style="font-family: 'Cascadia Code', sans-serif;">Flow</span>
            </div>
        </a>

        <!-- Le reste du code navbar reste inchangé -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/pfe/views/accueil.php">
                        <i class="fas fa-home"></i> Accueil
                    </a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/pfe/views/dashboard.php">
                            <i class="fas fa-columns"></i> Tableau de bord
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/pfe/views/statistiques.php">
                            <i class="fas fa-chart-line "></i> Statistiques
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="/pfe/views/messages.php">
                            <i class="fas fa-comments"></i> Chat 
                            <?php
                            require_once __DIR__ . '/../controllers/MessageController.php';
                            $messageController = new MessageController();
                            $unreadCount = $messageController->getUnreadMessagesCount($_SESSION['user_id']);
                            if ($unreadCount > 0): ?>
                                <span class="message-indicator">
                                    <?php echo $unreadCount > 99 ? '99+' : $unreadCount; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>

                </li>

            </ul>
            <div class="d-flex align-items-center">
                <button id="themeToggle" class="btn btn-outline-primary me-2" onclick="toggleTheme()">
                    <i class="fas fa-moon"></i>
                </button>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/pfe/views/notification.php" class="nav-link position-relative">
                        <i class="fas fa-bell"></i>
                        <?php
                        require_once __DIR__ . '/../controllers/AdminController.php';
                        $adminController = new AdminController();
                        $unreadCount = $adminController->getUnreadNotificationsCount($_SESSION['user_id']);
                        if ($unreadCount > 0): ?>
                            <span class="position-absolute notification-badge">
                                <?php echo $unreadCount > 99 ? '99+' : $unreadCount; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="/pfe/views/auth/login.php" class="btn btn-outline-primary me-2">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>
                    <a href="/pfe/views/auth/register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Inscription
                    </a>

                <?php else: ?>
                    <div class="dropdown me-2">
                        <button class="btn btn-outline-primary" type="button" id="statusDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-circle user-status"></i>
                            <span class="status-text">Disponible</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item status-option" href="#" data-status="available">
                                    <i class="fas fa-circle text-success"></i> Disponible
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item status-option" href="#" data-status="busy">
                                    <i class="fas fa-circle text-danger"></i> Occupé
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item status-option" href="#" data-status="away">
                                    <i class="fas fa-circle text-warning"></i> Absent
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($_SESSION['username'] ?? 'Mon Compte'); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="/pfe/views/profile.php">
                                    <i class="fas fa-user-circle me-2"></i> Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/pfe/views/settings.php">
                                    <i class="fas fa-cog me-2"></i> Paramètres
                                </a>
                            </li>
                           
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="/pfe/controllers/logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>

       
    </div>
</nav>
































<script>
    // Fonction pour basculer le thème
    function toggleTheme() {
        const body = document.body;
        const currentTheme = body.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';

        body.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);

        // Mettre à jour l'icône
        const themeIcon = document.querySelector('#themeToggle i');
        themeIcon.className = newTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
    }

    // Appliquer le thème au chargement
    document.addEventListener('DOMContentLoaded', function () {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.body.setAttribute('data-theme', savedTheme);
        const themeIcon = document.querySelector('#themeToggle i');
        themeIcon.className = savedTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
    });

    // Gérer le changement de statut
    document.addEventListener('DOMContentLoaded', function () {
        const statusOptions = document.querySelectorAll('.status-option');
        const statusIcon = document.querySelector('.user-status');
        const statusText = document.querySelector('.status-text');

        statusOptions.forEach(option => {
            option.addEventListener('click', function (e) {
                e.preventDefault();
                const status = this.dataset.status;

                fetch('/pfe/api/update-status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: status })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mettre à jour l'interface
                            statusText.textContent = this.textContent.trim();
                            statusIcon.className = 'fas fa-circle user-status ' +
                                (status === 'available' ? 'text-success' :
                                    status === 'busy' ? 'text-danger' : 'text-warning');
                        }
                    });
            });
        });
    });
    // Ajouter un effet de particules autour du logo
    document.addEventListener('DOMContentLoaded', function () {
        const logoContainer = document.querySelector('.logo-container');

        // Effet spécial au survol du logo
        logoContainer.addEventListener('mouseenter', function () {
            const logoGlow = document.querySelector('.logo-glow');
            logoGlow.style.animation = 'none';
            logoGlow.style.opacity = '1';
            logoGlow.style.transform = 'scale(1.3)';

            setTimeout(() => {
                logoGlow.style.animation = 'glow 3s infinite ease-in-out';
                logoGlow.style.opacity = '0.7';
                logoGlow.style.transform = 'scale(1)';
            }, 300);
        });
    });
</script>

<style>
    .navbar {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        padding: 1rem 0;
    }

    .navbar-brand,
    .nav-link,
    .navbar-nav .nav-link.active {
        color: white !important;
        opacity: 1;
        /* Ensure visibility */
    }


    .navbar-brand:hover,
    .nav-link:hover {
        opacity: 0.9;
    }

    .navbar .nav-link i.fa-bell {
        padding: 10px 13px;
        border: 1px solid white;
        border-radius: 20%;
        margin: 10px;
        position: relative;
    }

    .navbar .nav-link i.fa-bell:hover {

        background-color: white;
        color: #6366f1;
    }

    .notification-badge {
        position: absolute;
        top: 3px;
        right: -3px;
        background-color: #ff4444;
        color: white;
        border-radius: 12px;
        padding: 2px 6px;
        font-size: 12px;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid white;
        font-weight: initial;
        transform: translate(-50%, -50%);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    @keyframes pulse {
        0% {
            transform: translate(-50%, -50%) scale(1);
        }

        50% {
            transform: translate(-50%, -50%) scale(1.1);
        }

        100% {
            transform: translate(-50%, -50%) scale(1);
        }
    }

    .notification-badge {
        animation: pulse 2s infinite;
    }

    .nav-link:hover i.fa-bell {
        background-color: rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .notification-badge {
            top: -5px;
            right: -5px;
        }
    }

    .btn-outline-primary {
        color: white;
        border-color: white;
    }

    .btn-outline-primary:hover {
        background-color: white;
        color: #4f46e5;
        border-color: white;
    }

    .navbar .btn-primary {
        background-color: white;
        color: #4f46e5;
        border-color: white;
    }

    .navbar .btn-primary:hover {
        background-color: #e5e7eb;
        color: #4f46e5;
        border-color: #e5e7eb;
    }

    .user-status {
        font-size: 0.8em;
        margin-right: 0.5em;
    }

    .status-text {
        margin-left: 0.2em;
    }

    .navbar-toggler {
        border-color: rgba(255, 255, 255, 0.5);
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.7)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    @media (max-width: 991.98px) {
        .navbar-collapse {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }
    }

    .message-indicator {
        position: absolute;
        top: -5px;
        right: -8px;
        width: 16px;
        height: 16px;
        padding: 0 5px;
        background-color: #ff4444;
        border-radius: 9px;
        color: white;
        font-size: 12px;
        font-weight: initial;
        display: flex;
        align-items: center;
        border: 1px solid white;
        justify-content: center;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: translate(-50%, -50%) scale(1);
        }

        50% {
            transform: translate(-50%, -50%) scale(1.1);
        }

        100% {
            transform: translate(-50%, -50%) scale(1);
        }
    }
</style>