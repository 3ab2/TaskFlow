<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit();
}

require_once "../config/database.php";

// Récupérer les données JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['status']) || !in_array($data['status'], ['available', 'busy', 'away'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Statut invalide']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // Mettre à jour le statut dans la base de données
    $query = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $success = $stmt->execute([$data['status'], $_SESSION['user_id']]);

    if ($success) {
        $_SESSION['status'] = $data['status'];
        echo json_encode([
            'success' => true,
            'message' => 'Statut mis à jour avec succès',
            'data' => ['status' => $data['status']]
        ]);
    } else {
        throw new Exception('Erreur lors de la mise à jour du statut');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la mise à jour du statut: ' . $e->getMessage()
    ]);
}
