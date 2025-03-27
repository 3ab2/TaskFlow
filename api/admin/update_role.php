<?php
require_once __DIR__ . '/../../includes/config.php';

try {
    $admin_email = "3ab2uelkarch2006@gmail.com";
    
    // Update the user role to admin
    $stmt = $db->prepare("UPDATE users SET role = 'admin' WHERE email = ?");
    $result = $stmt->execute([$admin_email]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Admin role updated successfully']);
    } else {
        echo json_encode(['error' => 'Failed to update admin role']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} 