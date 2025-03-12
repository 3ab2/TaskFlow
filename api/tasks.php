<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit();
}

require_once "../config/database.php";
require_once "../controllers/TaskController.php";

$taskController = new TaskController();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                $result = $taskController->getTask($_GET['id']);
            } else {
                $result = $taskController->getTasks();
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $taskController->createTask($data);
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $taskId = isset($_GET['id']) ? $_GET['id'] : null;
            
            if (!$taskId) {
                throw new Exception('ID de tâche non spécifié');
            }
            
            $result = $taskController->updateTask($taskId, $data);
            break;

        case 'DELETE':
            $taskId = isset($_GET['id']) ? $_GET['id'] : null;
            
            if (!$taskId) {
                throw new Exception('ID de tâche non spécifié');
            }
            
            $result = $taskController->deleteTask($taskId);
            break;

        default:
            throw new Exception('Méthode non supportée');
    }

    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
