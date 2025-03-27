<?php
require_once __DIR__ . '/../includes/config.php';

// Nouvel email / New email / البريد الإلكتروني الجديد
$new_admin_email = "3ab2uelkarch2006@gmail.com";

try {
    global $db;
    
    // Mettre à jour l'email de l'admin / Update admin email / تحديث البريد الإلكتروني للمشرف
    $stmt = $db->prepare("UPDATE users SET email = ? WHERE username = 'admin' AND role = 'admin'");
    $result = $stmt->execute([$new_admin_email]);
    
    if ($result && $stmt->rowCount() > 0) {
        echo "✅ Email de l'administrateur mis à jour avec succès ! / Admin email updated successfully! / تم تحديث البريد الإلكتروني للمشرف بنجاح!\n";
        echo "\n";
        echo "🔐 Nouveaux identifiants / New credentials / بيانات الاعتماد الجديدة:\n";
        echo "Email: " . $new_admin_email . "\n";
        echo "Password: admin123\n";
    } else {
        echo "❌ Aucun compte administrateur trouvé / No admin account found / لم يتم العثور على حساب مشرف\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur / Error / خطأ: " . $e->getMessage() . "\n";
} 