<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

header('Content-Type: application/json');

// Verify admin session
$admin = new AdminController();
if (!$admin->isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Validate inputs
if (!isset($_POST['user_id']) || !isset($_POST['role'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit();
}

$userId = intval($_POST['user_id']);
$role = $_POST['role'];

// Validate role value
if ($role !== 'user' && $role !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Invalid role value']);
    exit();
}

// Update user role
$success = $admin->updateUserRole($userId, $role);

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Role updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update role']);
}
?>