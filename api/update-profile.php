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

    $user_id = $_SESSION['user_id'];
    $updates = [];
    $params = [];

    // Traitement de la photo de profil
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/profile/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new Exception('Format de fichier non supporté. Formats acceptés : JPG, JPEG, PNG, GIF, WebP');
        }

        if ($_FILES['profile_picture']['size'] > 2 * 1024 * 1024) {
            throw new Exception('La taille du fichier ne doit pas dépasser 2MB');
        }

        $fileName = uniqid() . '.' . $fileExtension;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
            $updates[] = "profile_picture = ?";
            $params[] = $fileName;
        }
    }

    // Traitement des autres champs
    if (!empty($_POST['username'])) {
        $updates[] = "username = ?";
        $params[] = $_POST['username'];
    }

    if (!empty($_POST['email'])) {
        $updates[] = "email = ?";
        $params[] = $_POST['email'];
    }

    if (!empty($_POST['new_password'])) {
        if ($_POST['new_password'] === $_POST['confirm_password']) {
            $updates[] = "password = ?";
            $params[] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        } else {
            throw new Exception('Les mots de passe ne correspondent pas');
        }
    }

    if (!empty($updates)) {
        $params[] = $user_id;
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
    }

    // Mettre à jour la session
    $_SESSION['username'] = $_POST['username'] ?? null;
    $_SESSION['email'] = $_POST['email'] ?? null;

    echo json_encode([
        'success' => true,
        'message' => 'Profil mis à jour avec succès'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
