<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit();
}

require_once "../config/database.php";

try {
    $database = new Database();
    $db = $database->getConnection();

    // Récupérer les données du formulaire
    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $new_password = $_POST['new_password'] ?? null;

    // Validation des données
    if (empty($username) || empty($email)) {
        throw new Exception('Le nom d\'utilisateur et l\'email sont requis');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Format d\'email invalide');
    }

    // Vérifier si l'email existe déjà pour un autre utilisateur
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $_SESSION['user_id']]);
    if ($stmt->fetch()) {
        throw new Exception('Cet email est déjà utilisé');
    }

    // Construire la requête de mise à jour
    $updateFields = ['username = ?', 'email = ?'];
    $params = [$username, $email];

    // Ajouter le mot de passe s'il est fourni
    if (!empty($new_password)) {
        $updateFields[] = 'password = ?';
        $params[] = password_hash($new_password, PASSWORD_DEFAULT);
    }

    $params[] = $_SESSION['user_id'];

    // Mettre à jour l'utilisateur
    $query = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?";
    $stmt = $db->prepare($query);
    $success = $stmt->execute($params);

    if ($success) {
        // Mettre à jour la session
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        echo json_encode([
            'success' => true,
            'message' => 'Profil mis à jour avec succès'
        ]);
    } else {
        throw new Exception('Erreur lors de la mise à jour du profil');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
