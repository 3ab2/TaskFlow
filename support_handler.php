<?php
session_start();
require_once "config/database.php"; // Assure-toi que ce fichier contient la connexion à la BDD

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Vérifier si les champs sont vides
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header("Location: support.php");
        exit();
    }

    // Vérifier si l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Adresse email invalide.";
        header("Location: support.php");
        exit();
    }

    // Vérifier la connexion à la base de données
    if (!$conn) {
        $_SESSION['error'] = "Erreur de connexion à la base de données.";
        header("Location: support.php");
        exit();
    }

    // Insérer le message dans la base de données
    $sql = "INSERT INTO support_requests (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute([$name, $email, $subject, $message]);


        if ($stmt->rowCount() > 0) {

            $_SESSION['success'] = "Votre message a été envoyé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de l'envoi du message.";
        }
        // No need to close the statement as it is automatically closed when it goes out of scope

    } else {
        $_SESSION['error'] = "Erreur de préparation de la requête.";
    }

    // Envoyer un email de notification à l'utilisateur
    $to = $email;
    $subject = "Confirmation de votre message";
    $body = "Bonjour $name,\n\nVotre message a été envoyé avec succès à l'équipe de support.\n\nSujet: $subject\nMessage: $message\n\nMerci de nous avoir contactés.";
    $headers = "From: support@taskflow.com";

    if (mail($to, $subject, $body, $headers)) {
        $_SESSION['success'] = "Votre message a été envoyé avec succès. Un email de confirmation a été envoyé.";
    } else {
        $_SESSION['error'] = "Erreur lors de l'envoi de l'email de confirmation.";
    }

    // Fermer la connexion à la base de données
    $conn = null;


    // Rediriger vers la page de support avec un message
    header("Location: support.php");
    exit();
} else {
    $_SESSION['error'] = "Requête invalide.";
    header("Location: support.php");
    exit();
}
?>