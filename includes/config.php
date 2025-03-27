<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuration de la base de données / Database configuration / إعدادات قاعدة البيانات
$db_host = 'localhost';
$db_name = 'task_management';
$db_user = 'root';
$db_pass = '';

try {
    // Connexion à la base de données / Database connection / الاتصال بقاعدة البيانات
    $db = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

// Fonction pour vérifier si l'utilisateur est connecté
// Function to check if user is logged in
// دالة للتحقق مما إذا كان المستخدم مسجل الدخول
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur est un admin
// Function to check if user is admin
// دالة للتحقق مما إذا كان المستخدم مشرفًا
function isAdmin() {
    if (!isLoggedIn()) {
        return false;
    }
    global $db;
    $stmt = $db->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    return $user && $user['role'] === 'admin';
} 