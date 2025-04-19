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

// Collect update data
$updateData = [];
if (isset($_POST['title']) && !empty($_POST['title'])) {
    $updateData['title'] = $_POST['title'];
}
if (isset($_POST['message']) && !empty($_POST['message'])) {
    $updateData['message'] = $_POST['message'];
}
if (isset($_POST['type']) && in_array($_POST['type'], ['info', 'success', 'warning', 'error'])) {
    $updateData['type'] = $_POST['type'];
}
if (isset($_POST['priority']) && in_array($_POST['priority'], ['low', 'normal', 'high'])) {
    $updateData['priority'] = $_POST['priority'];
}

if (empty($updateData)) {
    http_response_code(400);
    echo json_encode(['error' => 'Aucune donnée valide à mettre à jour']);
    exit();
}

try {
    $result = $admin->updateNotification($notificationId, $updateData);
    
    if (isset($result['success']) && $result['success']) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $result['error'] ?? 'Échec de la mise à jour de la notification']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>