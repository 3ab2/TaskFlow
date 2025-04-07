<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    .navbar {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
        box-shadow: 0 2px 4px var(--shadow-color);
        padding: 0.5rem 0;
    }

    .navbar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('/pfe/assets/images/pattern.svg') repeat;
        opacity: 0.1;
    }

    .navbar-brand {
        font-weight: 600;
        color: white !important;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
    }

    .navbar-brand:hover {
        transform: translateY(-2px);
        color: white !important;
    }

    .navbar-brand i {
        color: #e9d5ff;
        font-size: 1.4rem;
    }

    .nav-link {
        position: relative;
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        margin: 0 0.1rem;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
    }

    .nav-link:hover {
        color: white !important;
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-1px);
    }

    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        background-color: #e9d5ff;
        transition: width 0.3s ease;
    }

    .nav-link:hover::after {
        width: 80%;
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        color: white !important;
        font-weight: 600;
    }

    .nav-link i {
        font-size: 1rem;
        width: 1rem;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .nav-link:hover i {
        transform: scale(1.1);
    }

    .navbar-toggler {
        border-color: rgba(255, 255, 255, 0.5);
        position: relative;
        z-index: 1;
        padding: 0.25rem 0.5rem;
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.9)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .navbar-nav {
        display: flex;
        align-items: center;
        gap: 0.2rem;
    }

    @media (max-width: 991.98px) {
        .navbar-collapse {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.75rem;
            border-radius: 8px;
            margin-top: 0.5rem;
        }

        .nav-link {
            margin: 0.25rem 0;
            padding: 0.5rem 1rem;
        }

        .navbar-nav {
            gap: 0;
        }
    }

    /* Animation pour les éléments de la navbar */
    .navbar-nav {
        animation: fadeInDown 0.5s ease-out;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="/pfe/views/admin/dashboard.php">
            <i class="fas fa-user-cog"></i>
            <strong><em>Taskflow Admin</em></strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>"
                        href="/pfe/views/admin/dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'add_user.php' ? 'active' : ''; ?>"
                        href="/pfe/views/admin/add_user.php">
                        <i class="fas fa-user-plus"></i>
                        <span>Ajouter Utilisateur</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'add_task.php' ? 'active' : ''; ?>"
                        href="/pfe/views/admin/add_task.php">
                        <i class="fas fa-plus-circle"></i>
                        <span>Nouvelle Tâche</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'send_notification.php' ? 'active' : ''; ?>"
                        href="/pfe/views/admin/send_notification.php">
                        <i class="fas fa-bell"></i>
                        <span>Envoyer Notification</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'statistics.php' ? 'active' : ''; ?>"
                        href="/pfe/views/admin/statistics.php">
                        <i class="fas fa-chart-bar"></i>
                        <span>Statistiques</span>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
            <li class="nav-item">
                    <a class="nav-link" href="#" id="themeToggle">
                        <i class="fas fa-moon" id="themeIcon"></i>
                    </a>
                </li>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const themeToggle = document.getElementById('themeToggle');
                        const themeIcon = document.getElementById('themeIcon');
                        
                        // Vérifier le thème actuel
                        const currentTheme = localStorage.getItem('theme') || 'light';
                        document.body.classList.toggle('dark-mode', currentTheme === 'dark');
                        themeIcon.className = currentTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';

                        // Gestionnaire de clic pour basculer le thème
                        themeToggle.addEventListener('click', function(e) {
                            e.preventDefault();
                            const isDark = document.body.classList.toggle('dark-mode');
                            themeIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
                            localStorage.setItem('theme', isDark ? 'dark' : 'light');
                        });
                    });
                </script>
                <style>
                    .dark-mode {
                        background-color: #1a1a1a;
                        color: #e0e0e0;
                    }
                    .dark-mode .navbar {
                        background-color: #2d2d2d;
                        border-bottom: 1px solid #404040;
                    }
                    .dark-mode .nav-link {
                        color: #e0e0e0;
                    }
                    .dark-mode .nav-link:hover {
                        color: #ffffff;
                        background-color: #404040;
                    }
                    .dark-mode .nav-link.active {
                        background-color: #404040;
                        color: #ffffff;
                    }
                    .dark-mode .card {
                        background-color: #2d2d2d;
                        border: 1px solid #404040;
                    }
                    .dark-mode .card-header {
                        background-color: #333333;
                        border-bottom: 1px solid #404040;
                    }
                    .dark-mode .form-control {
                        background-color: #333333;
                        border: 1px solid #404040;
                        color: #e0e0e0;
                    }
                    .dark-mode .form-control:focus {
                        background-color: #404040;
                        border-color: #666666;
                        color: #ffffff;
                    }
                    .dark-mode .btn {
                        background-color: #404040;
                        border-color: #666666;
                        color: #e0e0e0;
                    }
                    .dark-mode .btn:hover {
                        background-color: #4d4d4d;
                        border-color: #808080;
                        color: #ffffff;
                    }
                    .dark-mode .table {
                        color: #ffffff;
                        background-color: #333333;
                    }
                    .dark-mode .table td,
                    .dark-mode .table th {
                        border-color: #404040;
                        background-color: #333333;
                        color: #ffffff;
                    }
                    .dark-mode .list-group-item {
                        background-color: #2d2d2d;
                        border-color: #404040;
                        color: #e0e0e0;
                    }
                    .dark-mode .list-group-item:hover {
                        background-color: #404040;
                    }
                    .dark-mode .list-group-item.active {
                        background-color: #0d6efd;
                        border-color: #0d6efd;
                    }
                    .dark-mode .form-label {
                        color: #ffffff;
                    }
                </style>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'profile.php' ? 'active' : ''; ?>"
                        href="/pfe/views/admin/profile.php">
                        <i class="fas fa-user-circle"></i>
                        
                    </a>
                </li>
              
            </ul>
        </div>
    </div>
</nav>