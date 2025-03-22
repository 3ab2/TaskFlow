<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg fixed-top d-inline-block align-top" >
    <div class="container">
        <a class="navbar-brand" href="/pfe/index.php">
            <img src="/pfe/assets/images/poudre.png" width="35" height="35" class="d-inline-block align-top" alt="Logo de TaskFlow">
            <strong><em>TASKFLOW</em></strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/pfe/index.php">
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
                        <i class="fas fa-chart-line "></i></i> Statistiques
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                  <a class="nav-link" href="/pfe/views/messages.php">
                  <i class="fas fa-comments"></i> Messagerie
    </a>
</li>

                <?php endif; ?>
            
</li>

            </ul>
            <div class="d-flex align-items-center">
                <button id="themeToggle" class="btn btn-outline-primary me-2" onclick="toggleTheme()">
                    <i class="fas fa-moon"></i>
                </button>
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
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
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
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="/pfe/controllers/logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>>
    <div class="container">
        <a class="navbar-brand" href="/pfe/index.php">
            <i class="fas fa-tasks"></i> TaskFlow
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/pfe/index.php">
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
                  <a class="nav-link" href="/pfe/views/messages.php">
                  <i class="fas fa-envelope"></i> Messagerie
    </a>
</li>

                <?php endif; ?>
            
</li>

            </ul>
            <div class="d-flex align-items-center">
                <button id="themeToggle" class="btn btn-outline-primary me-2" onclick="toggleTheme()">
                    <i class="fas fa-moon"></i>
                </button>
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
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
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
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="/pfe/controllers/logout.php">
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
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.body.setAttribute('data-theme', savedTheme);
    const themeIcon = document.querySelector('#themeToggle i');
    themeIcon.className = savedTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
});

// Gérer le changement de statut
document.addEventListener('DOMContentLoaded', function() {
    const statusOptions = document.querySelectorAll('.status-option');
    const statusIcon = document.querySelector('.user-status');
    const statusText = document.querySelector('.status-text');
    
    statusOptions.forEach(option => {
        option.addEventListener('click', function(e) {
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
}

.navbar-brand:hover,
.nav-link:hover {
    opacity: 0.9;
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
</style>