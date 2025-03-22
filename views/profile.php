<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /pfe/views/auth/login.php');
    exit();
}

require_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Récupérer les informations de l'utilisateur
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - TaskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-card i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="profile-header">
        <div class="container text-center">
            <img src="../assets/images/profil.png"
                alt="Photo de profil" class="profile-picture mb-3">
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p class="mb-0"><?php echo htmlspecialchars($user['email']); ?></p>
        </div>
    </div>

    <div class="container py-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Informations personnelles</h5>
                        <form id="profileForm">
                            <div class="mb-3">
                                <label class="form-label">Nom d'utilisateur</label>
                                <input type="text" class="form-control" name="username"
                                    value="<?php echo htmlspecialchars($user['username']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="<?php echo htmlspecialchars($user['email']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" name="new_password">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" name="confirm_password">
                            </div>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="stat-card text-center">
                            <i class="fas fa-tasks text-primary"></i>
                            <h3 class="task-count">0</h3>
                            <p>Tâches totales</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card text-center">
                            <i class="fas fa-check-circle text-success"></i>
                            <h3 class="completed-count">0</h3>
                            <p>Tâches terminées</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card text-center">
                            <i class="fas fa-clock text-warning"></i>
                            <h3 class="in-progress-count">0</h3>
                            <p>Tâches en cours</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card text-center">
                            <i class="fas fa-calendar text-danger"></i>
                            <h3 class="overdue-count">0</h3>
                            <p>Tâches en retard</p>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Activité récente</h5>
                        <div id="activityTimeline" class="timeline">
                            <!-- Les activités seront chargées dynamiquement -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                        <li><a href="#">Support</a></li>
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



    < <script>
        document.addEventListener('DOMContentLoaded', function() {
        // Charger les statistiques
        fetch('/pfe/api/tasks.php')
        .then(response => response.json())
        .then(data => {
        if (data.success) {
        updateTaskStats(data.data);
        }
        });

        // Gérer la mise à jour du profil
        document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        // Vérifier les mots de passe
        if (formData.get('new_password') !== formData.get('confirm_password')) {
        alert('Les mots de passe ne correspondent pas !');
        return;
        }

        fetch('/pfe/api/update-profile.php', {
        method: 'POST',
        body: formData
        })
        .then(response => response.json())
        .then(data => {
        if (data.success) {
        alert('Profil mis à jour avec succès !');
        location.reload();
        } else {
        alert(data.message || 'Erreur lors de la mise à jour du profil');
        }
        });
        });
        });

        function updateTaskStats(tasks) {
        const stats = {
        total: tasks.length,
        completed: tasks.filter(t => t.status === 'completed').length,
        in_progress: tasks.filter(t => t.status === 'in_progress').length,
        overdue: tasks.filter(t => new Date(t.deadline) < new Date() && t.status !=='completed' ).length };
            document.querySelector('.task-count').textContent=stats.total;
            document.querySelector('.completed-count').textContent=stats.completed;
            document.querySelector('.in-progress-count').textContent=stats.in_progress;
            document.querySelector('.overdue-count').textContent=stats.overdue; } </script>

</body>

</html>