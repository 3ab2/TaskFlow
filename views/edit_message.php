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

// Vérification si l'ID du message est fourni
if (!isset($_GET['id'])) {
    header("Location: /pfe/views/messages.php");
    exit();
}

$message_id = $_GET['id'];

// Récupérer le message depuis la base de données
$query = "SELECT * FROM messages WHERE id = :id AND user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $message_id);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$message = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérification si le message existe
if (!$message) {
    header("Location: /pfe/views/messages.php");
    exit();
}

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];

    // Mettre à jour le message dans la base de données
    $update_query = "UPDATE messages SET content = :content WHERE id = :id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->bindParam(':content', $content);
    $update_stmt->bindParam(':id', $message_id);
    $update_stmt->execute();

    header("Location: /pfe/views/messages.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Message - TaskFlow</title>
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
</head>

<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container " style="margin-top: 100px;">

        <h4 class="text-center"><i class="fas fa-edit me-2"></i> Vous pouvez modifier votre Message ici</h4>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="content" class="form-label">Contenu du Message</label>
                <textarea id="content" name="content" class="form-control" rows="4"
                    required><?= htmlspecialchars($message['content']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à Jour</button>
            <a href="/pfe/views/messages.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
    <!-- Footer -->

    <footer style="margin-top: 40px;">
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