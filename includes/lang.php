<?php
session_start();

// Définir la langue par défaut
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'fr'; // Par défaut en français
}

// Vérifier si l'utilisateur change de langue
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    if (in_array($lang, ['fr', 'en', 'ar'])) {
        $_SESSION['lang'] = $lang;
    }
}

// Charger les fichiers de langue
$langFile = __DIR__ . "/../languages/" . $_SESSION['lang'] . ".json";
if (file_exists($langFile)) {
    $translations = json_decode(file_get_contents($langFile), true);
} else {
    $translations = [];
}

// Fonction pour récupérer les traductions
function __($key) {
    global $translations;
    return $translations[$key] ?? $key;
}
?>
