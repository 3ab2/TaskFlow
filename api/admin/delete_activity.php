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

// Check if activity ID is provided
if (!isset($_POST['activity_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Activity ID is required']);
    exit();
}

$activityId = intval($_POST['activity_id']);

// Delete the activity
try {
    $result = $admin->deleteActivity($activityId);
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Activity not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete activity']);
} 