<?php
session_start();
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

$taskId = $_POST['task_id'] ?? null;

if (!$taskId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing task_id parameter']);
    exit();
}

if ($admin->deleteTask($taskId)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete task']);
} 