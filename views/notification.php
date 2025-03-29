<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../controllers/AdminController.php';

$adminController = new AdminController();
$notifications = $adminController->getUserNotifications();

// Handle filtering
$typeFilter = $_GET['type'] ?? '';
$priorityFilter = $_GET['priority'] ?? '';

if ($typeFilter || $priorityFilter) {
    $notifications = array_filter($notifications, function ($notification) use ($typeFilter, $priorityFilter) {
        $typeMatch = !$typeFilter || $notification['type'] === $typeFilter;
        $priorityMatch = !$priorityFilter || $notification['priority'] === $priorityFilter;
        return $typeMatch && $priorityMatch;
    });
}

// Ajouter au début du fichier après la récupération des notifications
if (!empty($notifications)) {
    $adminController->markNotificationsAsRead($_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Taskflow</title>
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/dashboard.css">

    <style>
        h2,
        p {
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container mt-5" style="margin-top: 10px;">
        <h2>Notifications</h2>
        <p>Cette page contient les notifications envoyées par l'equipe de support.</p>

        <?php if (empty($notifications)): ?>
            <div class="alert alert-info">Aucune notification à afficher.</div>
        <?php else: ?>
            <form action="" method="GET">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="type" class="form-label">Type de notification</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">Tous</option>
                            <option value="info" <?php echo $typeFilter === 'info' ? 'selected' : ''; ?>>
                                <i class="fa-solid fa-circle-info me-2"></i> Information
                            </option>
                            <option value="success" <?php echo $typeFilter === 'success' ? 'selected' : ''; ?>>
                                <i class="fa-solid fa-circle-check me-2"></i> Succès
                            </option>
                            <option value="warning" <?php echo $typeFilter === 'warning' ? 'selected' : ''; ?>>
                                <i class="fa-solid fa-triangle-exclamation me-2"></i> Avertissement
                            </option>
                            <option value="error" <?php echo $typeFilter === 'error' ? 'selected' : ''; ?>>
                                <i class="fa-solid fa-circle-xmark me-2"></i> Erreur
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="priority" class="form-label">Priorité</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="">Toutes</option>
                            <option value="low" <?php echo $priorityFilter === 'low' ? 'selected' : ''; ?>>
                                <i class="fa-solid fa-arrow-down me-2"></i> Basse
                            </option>
                            <option value="normal" <?php echo $priorityFilter === 'normal' ? 'selected' : ''; ?>>
                                <i class="fa-solid fa-arrow-right me-2"></i> Normale
                            </option>
                            <option value="high" <?php echo $priorityFilter === 'high' ? 'selected' : ''; ?>>
                                <i class="fa-solid fa-arrow-up me-2"></i> Haute
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-50">
                            <i class="fa-solid fa-filter me-2"></i> Filtrer
                        </button>
                        <button type="button" class="btn btn-secondary w-50 ms-2"
                            onclick="window.location.href='/pfe/views/notification.php'">
                            <i class="fa-solid fa-rotate-left me-2"></i> Réinitialiser
                        </button>
                    </div>
                </div>
            </form>

            <ul class="list-group">
                <?php foreach ($notifications as $notification): ?>
                    <li
                        class="list-group-item <?php echo $notification['type'] === 'info' ? 'bg-info' : ($notification['type'] === 'success' ? 'bg-success' : ($notification['type'] === 'warning' ? 'bg-warning' : 'bg-danger')); ?>">
                        <strong><?php echo htmlspecialchars($notification['title']); ?></strong><br>
                        <?php echo htmlspecialchars($notification['message']); ?><br>
                        <small class="text-muted">Reçu le <?php echo htmlspecialchars($notification['created_at']); ?></small>
                        <?php if ($notification['type'] === 'info'): ?>
                            <i class="fa-solid fa-circle-info ms-2"></i>
                        <?php elseif ($notification['type'] === 'success'): ?>
                            <i class="fa-solid fa-circle-check ms-2"></i>
                        <?php elseif ($notification['type'] === 'warning'): ?>
                            <i class="fa-solid fa-triangle-exclamation ms-2"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-circle-xmark ms-2"></i>
                        <?php endif; ?>
                        <span class="float-end">
                            <?php if ($notification['priority'] === 'low'): ?>
                                <i class="fa-solid fa-arrow-down text-secondary"></i>
                            <?php elseif ($notification['priority'] === 'normal'): ?>
                                <i class="fa-solid fa-arrow-right text-primary"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-arrow-up text-danger"></i>
                            <?php endif; ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/pfe/assets/js/main.js"></script>
    <script src="/pfe/assets/js/tasks.js"></script>
</body>

</html>