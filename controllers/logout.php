<?php
require_once "AuthController.php";

$auth = new AuthController();

session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Rediriger vers la page d'accueil
header('Location: /pfe/index.php');
exit();
?>
