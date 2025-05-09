<?php
session_start();
require_once "AuthController.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $result = $auth->login($email, $password);
    
    if ($result['success']) {
        // Vérifier si l'utilisateur est un admin
        $user = $auth->getUserByEmail($email);
        if ($user && $user['role'] === 'admin') {
            header('Location: /pfe/views/admin/dashboard.php');
            exit();
        } else {
            // Redirect to loading.php with target redirect to accueil.php
            header('Location: /pfe/views/loading.php?redirect=/pfe/views/accueil.php');
            exit();
        }
    } else {
        $_SESSION['error'] = implode('<br>', $result['errors']);
        header('Location: /pfe/views/auth/login.php');
        exit();
    }
} else {
    header('Location: /pfe/views/auth/login.php');
    exit();
}
?>
