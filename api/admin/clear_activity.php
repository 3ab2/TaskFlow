<?php
session_start();
require_once __DIR__ . '/../../controllers/AdminController.php';

header('Content-Type: application/json');

// Verify admin authentication
$admin = new AdminController();
if (!$admin->isAdmin()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// Clear all activities
try {
    $result = $admin->clearAllActivities();
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to clear activities']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to clear activities']);
} 