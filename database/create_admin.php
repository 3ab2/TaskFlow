<?php
require_once __DIR__ . '/../includes/config.php';

// Configuration de l'administrateur / Admin configuration / إعداد المشرف
$admin_username = "admin";
$admin_password = "admin123"; // Vous devrez changer ce mot de passe / You should change this password / يجب عليك تغيير كلمة المرور
$admin_email = "3ab2uelkarch2006@gmail.com";

try {
    global $db;
    
    // Vérifier si l'utilisateur existe déjà / Check if user already exists / التحقق مما إذا كان المستخدم موجودًا بالفعل
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$admin_username, $admin_email]);
    
    if ($stmt->fetch()) {
        echo "❌ L'administrateur existe déjà / Admin already exists / المشرف موجود بالفعل\n";
        exit();
    }
    
    // Hacher le mot de passe / Hash the password / تشفير كلمة المرور
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
    
    // Insérer l'administrateur / Insert admin / إدراج المشرف
    $stmt = $db->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, 'admin', NOW())");
    $stmt->execute([$admin_username, $admin_email, $hashed_password]);
    
    echo "✅ Administrateur créé avec succès ! / Admin created successfully! / تم إنشاء المشرف بنجاح!\n";
    echo "\n";
    echo "🔐 Identifiants / Credentials / بيانات الاعتماد:\n";
    echo "Username: " . $admin_username . "\n";
    echo "Password: " . $admin_password . "\n";
    echo "\n";
    echo "⚠️ IMPORTANT: Changez le mot de passe après la première connexion!\n";
    echo "⚠️ IMPORTANT: Change the password after first login!\n";
    echo "⚠️ هام: قم بتغيير كلمة المرور بعد أول تسجيل دخول!\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur / Error / خطأ: " . $e->getMessage() . "\n";
} 