<?php
session_start();
require_once "../config/database.php";

// Création d'une instance de la classe Database
$database = new Database();
$pdo = $database->getConnection(); // Récupération de la connexion PDO

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: /pfe/views/auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les messages depuis la base de données
$query = "SELECT messages.*, users.username 
          FROM messages 
          JOIN users ON messages.user_id = users.id 
          ORDER BY messages.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie - TaskFlow</title>
    
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4"><i class="fas fa-comments"></i> Messagerie</h2>

        <div class="card">
            <div class="card-body">
                <!-- Zone d'affichage des messages -->
                <div class="chat-box p-3" style="max-height: 400px; overflow-y: auto;">
                    <?php foreach ($messages as $message): ?>
                        <div class="d-flex <?php echo ($message['user_id'] == $user_id) ? 'justify-content-end' : 'justify-content-start'; ?>">
                            <div class="alert <?php echo ($message['user_id'] == $user_id) ? 'alert-primary' : 'alert-secondary'; ?> w-75">
                                <small class="text-muted"><?= htmlspecialchars($message['username']); ?> - <?= $message['created_at']; ?></small>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($message['content'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Formulaire d'envoi -->
                <form action="send_message.php" method="POST" class="mt-3">
                    <div class="input-group">
                        <input type="text" name="message" class="form-control" placeholder="Écrire un message..." required>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
