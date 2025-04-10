<?php
session_start();
require_once '../../config/database.php';

// Créer une instance de la classe Database
$database = new Database();
$conn = $database->getConnection();

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Vérifier si le token est valide et n'a pas expiré
    $query = "SELECT user_id FROM password_resets WHERE token = :token AND expires_at > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $reset['user_id'];
        
        // Supprimer le token utilisé
        $query = "DELETE FROM password_resets WHERE token = :token";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        // Connecter l'utilisateur
        $_SESSION['user_id'] = $userId;
        
        // Rediriger vers le tableau de bord
        header("Location: /pfe/views/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Le lien de réinitialisation est invalide ou a expiré.";
        header("Location: /pfe/views/auth/reset_password.php");
        exit();
    }
} else {
    header("Location: /pfe/views/auth/reset_password.php");
    exit();
}
?> 