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

// Supprimer le message de la base de données
$query = "DELETE FROM messages WHERE id = :id AND user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $message_id);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

// Rediriger vers la page des messages
header("Location: /pfe/views/messages.php");
exit();
?>
