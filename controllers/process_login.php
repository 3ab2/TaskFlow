<?php
session_start();
require_once "AuthController.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $result = $auth->login($email, $password);
    
    if ($result['success']) {
        // Redirection vers le tableau de bord
        header('Location: /pfe/views/dashboard.php');
        exit();
    } else {
        $_SESSION['error'] = implode('<br>', $result['errors']);
        header('Location: /pfe/views/auth/login.php');
        exit();
    }
} else {
    header('Location: /pfe/views/auth/login.php');
    exit();
}
