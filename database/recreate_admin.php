<?php
require_once __DIR__ . '/../includes/config.php';

// Configuration de l'administrateur / Admin configuration / إعداد المشرف
$admin_username = "admin";
$admin_password = "admin123";
$admin_email = "3ab2uelkarch2006@gmail.com";

try {
    global $db;
    
    // Supprimer l'ancien admin / Delete old admin / حذف المشرف القديم
    $stmt = $db->prepare("DELETE FROM users WHERE username = 'admin' OR email = 'admin@example.com'");
    $stmt->execute();
    
    // Hacher le mot de passe / Hash the password / تشفير كلمة المرور
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
    
    // Créer le nouvel admin / Create new admin / إنشاء مشرف جديد
    $stmt = $db->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, 'admin', NOW())");
    $stmt->execute([$admin_username, $admin_email, $hashed_password]);
    
    echo "✅ Administrateur recréé avec succès ! / Admin recreated successfully! / تم إعادة إنشاء المشرف بنجاح!\n";
    echo "\n";
    echo "🔐 Nouveaux identifiants / New credentials / بيانات الاعتماد الجديدة:\n";
    echo "Email: " . $admin_email . "\n";
    echo "Password: " . $admin_password . "\n";
    echo "\n";
    echo "⚠️ IMPORTANT: Changez le mot de passe après la première connexion!\n";
    echo "⚠️ IMPORTANT: Change the password after first login!\n";
    echo "⚠️ هام: قم بتغيير كلمة المرور بعد أول تسجيل دخول!\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur / Error / خطأ: " . $e->getMessage() . "\n";
} 