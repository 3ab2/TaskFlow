<?php
require_once __DIR__ . '/../includes/config.php';

try {
    global $db;
    
    // Vérifier si l'utilisateur admin existe / Check if admin user exists / التحقق من وجود مستخدم المشرف
    $stmt = $db->prepare("SELECT id, role FROM users WHERE username = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch();
    
    if ($admin) {
        // Mettre à jour le rôle en admin / Update role to admin / تحديث الدور إلى مشرف
        $stmt = $db->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
        $stmt->execute([$admin['id']]);
        
        echo "✅ Rôle administrateur corrigé avec succès ! / Admin role fixed successfully! / تم تصحيح دور المشرف بنجاح!\n";
        echo "\n";
        echo "🔐 Identifiants / Credentials / بيانات الاعتماد:\n";
        echo "Email: 3ab2uelkarch2006@gmail.com\n";
        echo "Password: admin123\n";
    } else {
        echo "❌ Aucun utilisateur admin trouvé / No admin user found / لم يتم العثور على مستخدم مشرف\n";
        echo "Veuillez d'abord créer le compte admin / Please create the admin account first / يرجى إنشاء حساب المشرف أولاً\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur / Error / خطأ: " . $e->getMessage() . "\n";
} 