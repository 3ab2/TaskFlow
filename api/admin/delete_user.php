<?php
require_once __DIR__ . '/../../controllers/AdminController.php';

header('Content-Type: application/json');

$admin = new AdminController();

if (!$admin->isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$userId = $_POST['user_id'] ?? null;

if (!$userId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing user_id parameter']);
    exit();
}

if ($admin->deleteUser($userId)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete user']);
} 