<?php
session_start();
require_once "../config/database.php";
require_once "../controllers/MessageController.php";

// Création d'une instance de la classe Database
$database = new Database();
$pdo = $database->getConnection(); // Récupération de la connexion PDO

// Création d'une instance de MessageController
$messageController = new MessageController();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: /pfe/views/auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Marquer les messages comme lus
$messageController->markMessagesAsRead($user_id);

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
    <style>
        .message-actions {
            display: none;
        }

        .alert:hover .message-actions {
            display: block;
            cursor: pointer;
        }

        /* Nouveau style moderne pour les messages */
        .chat-box {
            background: rgba(133, 207, 170, 0.35);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .alert {
            border:none;
            border-bottom: 1px solid rgb(22, 198, 60);
            border-right: 1px solid rgb(22, 198, 60);
            border-radius: 60px 60px 60px 0px;
            padding: 10px 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
            max-width: 80%;
        }

        .alert:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .alert-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .alert-info {
            background: linear-gradient(135deg,rgb(108, 125, 120),rgb(154, 217, 199));
            color: white;
        }

        .message-actions {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .alert:hover .message-actions {
            opacity: 1;
        }

        .message-actions .btn {
            color: white;
            background: rgba(255,255,255,0.2);
            border: none;
            height: 30px;
            width: 30px;
            padding: 5px 10px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .message-actions .btn:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }

        .text-muted {
            font-size: 0.8rem;
            opacity: 0.8;
           
        }

        .input-group {
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .input-group input {
            border: none;
            padding: 15px 20px;
        }

        .input-group .btn {
            border-radius: 0 25px 25px 0;
            padding: 15px 25px;
        }
    </style>
</head>

<body style="margin-top: 10px;">
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5" >
        <h2 class="text-center mb-4"> Messagerie</h2>
        <p class="text-center mb-4">Bienvenue dans votre espace de messagerie, où vous pouvez échanger des messages avec vos collègues et collaborateurs.</p>


        <div class="card">
            <div class="card-body">
                <!-- Zone d'affichage des messages -->
                <div class="chat-box p-3" style="max-height:350px; overflow-y: auto;">
                    <?php foreach ($messages as $message): ?>
                        <div
                            class="d-flex <?php echo ($message['user_id'] == $user_id) ? 'justify-content-end' : 'justify-content-start'; ?>">
                            <div class="alert <?php echo ($message['user_id'] == $user_id) ? 'alert-primary' : 'alert-info'; ?> w-75 ">
                                <i class="fas fa-user-circle me-2"></i>
                                <small class="text-muted"
                                    style="color: <?= '#' . substr(md5($message['username']), 0, 6); ?>; font-weight: bold;"><?= htmlspecialchars($message['username']); ?>
                                    
                                   </small> <i style="color: rgb(0, 0, 0, 0.5); font-size: 0.7rem;">  <?= date('l  H:i', strtotime($message['created_at'])); ?></i>

                                <p class="mb-0"><?= nl2br(htmlspecialchars($message['content'])); ?></p>
                                <div class="message-actions" style="position: relative;">
                                    <?php if ($message['user_id'] == $user_id): ?>
                                        <div style="position: absolute; bottom: 24px; right: 0;">
                                            <a href="edit_message.php?id=<?= $message['id']; ?>" class="btn btn-sm"><i
                                                    class="fas fa-edit"></i></a>
                                        </div>
                                        <div style="position: absolute; bottom: -8px; right: 0;">
                                            <a href="delete_message.php?id=<?= $message['id']; ?>" class="btn btn-sm"><i
                                                    class="fas fa-trash-alt"></i></a>
                                        </div>
                                    <?php endif; ?>
                                </div>


                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Formulaire d'envoi -->
                <form action="send_message.php" method="POST" class="mt-3">
                    <div class="input-group">
                        <input type="text" name="message" class="form-control" placeholder="Écrire un message..."
                            required>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </form>
            </div>
        </div>
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
                <p>&copy; <?php echo date('Y '); ?> TaskFlow. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>