<?php
session_start();
require_once __DIR__ . '/../../controllers/AdminController.php';

header('Content-Type: application/json');

$admin = new AdminController();

if (!$admin->isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Accès non autorisé']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit();
}

$notificationId = $_POST['notification_id'] ?? null;

if (!$notificationId) {
    http_response_code(400);
    echo json_encode(['error' => 'ID notification manquant']);
    exit();
}

try {
    $result = $admin->deleteNotification($notificationId);
    
    if (isset($result['success']) && $result['success']) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $result['error'] ?? 'Échec de la suppression de la notification']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>