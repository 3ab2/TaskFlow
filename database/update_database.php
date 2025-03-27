<?php
require_once __DIR__ . '/../includes/config.php';

try {
    // Lire le contenu du fichier SQL
    $sql = file_get_contents(__DIR__ . '/create_notifications.sql');
    
    // Exécuter les requêtes SQL
    $db->exec($sql);
    
    echo "Table notifications créée avec succès!\n";
} catch (PDOException $e) {
    echo "Erreur lors de la création de la table: " . $e->getMessage() . "\n";
} 