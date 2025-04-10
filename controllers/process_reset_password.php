<?php
session_start();
require_once '../config/database.php';

// Créer une instance de la classe Database
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Vérifier si l'email existe dans la base de données
    $query = "SELECT id FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $user['id'];
        
        // Générer un token unique
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Stocker le token dans la base de données
        $query = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires_at', $expires);
        $stmt->execute();
        
        // Créer le lien de réinitialisation
        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/pfe/views/auth/reset_password_confirm.php?token=" . $token;
        
        // Envoyer l'email
        $to = $email;
        $subject = "Réinitialisation de votre mot de passe - TaskFlow";
        $message = "Bonjour,\n\n";
        $message .= "Vous avez demandé la réinitialisation de votre mot de passe.\n";
        $message .= "Cliquez sur le lien suivant pour accéder directement à votre tableau de bord :\n";
        $message .= $resetLink . "\n\n";
        $message .= "Ce lien expirera dans 1 heure.\n\n";
        $message .= "Cordialement,\nL'équipe TaskFlow";
        
        $headers = "From: noreply@taskflow.com\r\n";
        $headers .= "Reply-To: noreply@taskflow.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $subject, $message, $headers)) {
            $_SESSION['message'] = "Un email contenant le lien de réinitialisation a été envoyé à votre adresse email.";
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de l'envoi de l'email.";
        }
    } else {
        $_SESSION['error'] = "Aucun compte n'est associé à cette adresse email.";
    }
    
    header("Location: /pfe/views/auth/reset_password.php");
    exit();
} else {
    $_SESSION['error'] = "Veuillez fournir une adresse e-mail valide.";
    header('Location: /pfe/views/auth/reset_password.php');
    exit();
}
?>