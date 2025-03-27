<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../controllers/AdminController.php';

$adminController = new AdminController();
$notifications = $adminController->getUserNotifications();
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
        h2,p{
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container mt-5">
        <h2>Notifications</h2>
        <p>Cette page contient les notifications envoyées par l'equipe de support.</p>
        
        <?php if (empty($notifications)): ?>
            <div class="alert alert-info">Aucune notification à afficher.</div>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($notifications as $notification): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($notification['title']); ?></strong><br>
                        <?php echo htmlspecialchars($notification['message']); ?><br>
                        <small class="text-muted">Reçu le <?php echo htmlspecialchars($notification['created_at']); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/pfe/assets/js/main.js"></script>
    <script src="/pfe/assets/js/tasks.js"></script>
</body>
</html>
