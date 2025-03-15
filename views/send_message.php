<?php
session_start();
require_once "../config/database.php";

// Création d'une instance de la classe Database et récupération de la connexion PDO
$database = new Database();
$pdo = $database->getConnection();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: /pfe/views/auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["message"])) {
    $user_id = $_SESSION['user_id'];
    $message = htmlspecialchars($_POST["message"]); // Protection contre les failles XSS

    // Insérer le message dans la base de données
    $query = "INSERT INTO messages (user_id, content, created_at) VALUES (:user_id, :content, NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'user_id' => $user_id,
        'content' => $message
    ]);

    header("Location: messages.php");
    exit();
} else {
    echo "Erreur : Message vide ou méthode non autorisée.";
}
?>