<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    $_SESSION['message'] = "Un lien de réinitialisation a été envoyé à $email.";
    header('Location: /pfe/views/auth/reset_password.php');
    exit();
} else {
    $_SESSION['error'] = "Veuillez fournir une adresse e-mail valide.";
    header('Location: /pfe/views/auth/reset_password.php');
    exit();
}
?>