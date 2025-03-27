<?php
require_once __DIR__ . '/../includes/config.php';

try {
    global $db;
    
    // Créer la table users si elle n'existe pas
    // Create users table if it doesn't exist
    // إنشاء جدول المستخدمين إذا لم يكن موجودًا
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $db->exec($sql);
    
    echo "✅ Tables créées avec succès ! / Tables created successfully! / تم إنشاء الجداول بنجاح!\n";
    
} catch (PDOException $e) {
    die("❌ Erreur / Error / خطأ: " . $e->getMessage() . "\n");
} 