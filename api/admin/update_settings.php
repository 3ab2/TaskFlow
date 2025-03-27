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

$type = $_POST['type'] ?? '';
$data = $_POST;

$result = $admin->updateSettings($type, $data);

if (isset($result['error'])) {
    http_response_code(400);
    echo json_encode(['error' => $result['error']]);
} else {
    echo json_encode(['success' => true]);
} 