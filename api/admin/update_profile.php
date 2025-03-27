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

$data = [
    'username' => $_POST['username'] ?? '',
    'email' => $_POST['email'] ?? '',
    'new_password' => $_POST['new_password'] ?? '',
    'confirm_password' => $_POST['confirm_password'] ?? ''
];

// Validation
if (empty($data['username']) || empty($data['email'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Username and email are required']);
    exit();
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format']);
    exit();
}

$result = $admin->updateAdminProfile($data);

if (isset($result['error'])) {
    http_response_code(400);
    echo json_encode(['error' => $result['error']]);
} else {
    echo json_encode(['success' => true]);
} 