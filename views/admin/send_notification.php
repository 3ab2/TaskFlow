<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

$adminController = new AdminController();
// Récupérer la liste des utilisateurs pour le select
$users = $adminController->getAllUsers();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $recipients = $_POST['recipients'] ?? [];
    $type = $_POST['type'] ?? 'info';
    $priority = $_POST['priority'] ?? 'normal';
    
    // Validation
    $errors = [];
    if (empty($title)) $errors[] = "Le titre est requis";
    if (empty($message)) $errors[] = "Le message est requis";
    if (empty($recipients)) $errors[] = "Veuillez sélectionner au moins un destinataire";
    
    if (empty($errors)) {
        try {
            $result = $adminController->sendNotification($title, $message, $recipients, $type, $priority);
            if ($result) {
                $success = "Notification envoyée avec succès!";
                // Vider les champs après un envoi réussi
                $title = $message = '';
                $recipients = [];
                $type = 'info';
                $priority = 'normal';
            } else {
                $errors[] = "Erreur lors de l'envoi de la notification";
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoyer une Notification - Taskflow Admin</title>
    <link rel="shortcut icon" href="/pfe/assets/images/admin_logo.png" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <link rel="stylesheet" href="/pfe/assets/css/taskflow-custom.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/admin_navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card fade-in">
                    <div class="card-header text-white d-flex align-items-center">
                        <i class="fas fa-bell fa-fw fa-lg me-3"></i>
                        <h4 class="mb-0">Envoyer une notification</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0 ps-3">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="title" class="form-label">
                                        <i class="fas fa-heading fa-fw me-1"></i> Titre
                                    </label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="<?= htmlspecialchars($title ?? '') ?>" 
                                           placeholder="Titre de la notification" required>
                                    <div class="invalid-feedback">Le titre est requis</div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="type" class="form-label">
                                        <i class="fas fa-tag fa-fw me-1"></i> Type
                                    </label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="info" <?= ($type ?? 'info') === 'info' ? 'selected' : '' ?>>Information</option>
                                        <option value="success" <?= ($type ?? '') === 'success' ? 'selected' : '' ?>>Succès</option>
                                        <option value="warning" <?= ($type ?? '') === 'warning' ? 'selected' : '' ?>>Avertissement</option>
                                        <option value="danger" <?= ($type ?? '') === 'danger' ? 'selected' : '' ?>>Urgent</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">
                                    <i class="fas fa-align-left fa-fw me-1"></i> Message
                                </label>
                                <textarea class="form-control" id="message" name="message" 
                                          rows="4" placeholder="Contenu de la notification" required><?= htmlspecialchars($message ?? '') ?></textarea>
                                <div class="invalid-feedback">Le message est requis</div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="recipients" class="form-label">
                                        <i class="fas fa-users fa-fw me-1"></i> Destinataires
                                    </label>
                                    <select multiple class="form-select" id="recipients" name="recipients[]" 
                                            style="height: 120px;" required>
                                        <option value="all" <?= isset($recipients) && in_array('all', $recipients) ? 'selected' : '' ?>>Tous les utilisateurs</option>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?= $user['id'] ?>" <?= isset($recipients) && in_array($user['id'], $recipients) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($user['username'] ?? '') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Veuillez sélectionner au moins un destinataire</div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i> Maintenez la touche Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs utilisateurs
                                    </small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="priority" class="form-label">
                                        <i class="fas fa-flag fa-fw me-1"></i> Priorité
                                    </label>
                                    <select class="form-select" id="priority" name="priority" required>
                                        <option value="low" <?= ($priority ?? '') === 'low' ? 'selected' : '' ?>>Basse</option>
                                        <option value="normal" <?= ($priority ?? 'normal') === 'normal' ? 'selected' : '' ?>>Normale</option>
                                        <option value="high" <?= ($priority ?? '') === 'high' ? 'selected' : '' ?>>Haute</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer la notification
                                </button>
                                <a href="/pfe/views/admin/dashboard.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validation des formulaires Bootstrap
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    } else {
                        // Afficher l'animation de chargement
                        const submitBtn = document.getElementById('submit-btn');
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Envoi en cours...';
                        submitBtn.disabled = true;
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
        
        // Mise en évidence du type de notification
        document.getElementById('type').addEventListener('change', function() {
            const typeSelect = this;
            const selectedValue = typeSelect.value;
            
            // Reset classes
            typeSelect.classList.remove('border-info', 'border-success', 'border-warning', 'border-danger');
            
            // Add appropriate class based on selection
            if (selectedValue === 'info') {
                typeSelect.classList.add('border-info');
            } else if (selectedValue === 'success') {
                typeSelect.classList.add('border-success');
            } else if (selectedValue === 'warning') {
                typeSelect.classList.add('border-warning');
            } else if (selectedValue === 'danger') {
                typeSelect.classList.add('border-danger');
            }
        });

        // Mise en évidence de la priorité sélectionnée
        document.getElementById('priority').addEventListener('change', function() {
            const prioritySelect = this;
            const selectedValue = prioritySelect.value;
            
            // Reset classes
            prioritySelect.classList.remove('border-info', 'border-primary', 'border-danger');
            
            // Add appropriate class based on selection
            if (selectedValue === 'high') {
                prioritySelect.classList.add('border-danger');
            } else if (selectedValue === 'normal') {
                prioritySelect.classList.add('border-primary');
            } else {
                prioritySelect.classList.add('border-info');
            }
        });

        // Initialiser les couleurs des sélecteurs au chargement
        window.addEventListener('DOMContentLoaded', function() {
            // Initialiser le type
            const typeSelect = document.getElementById('type');
            const typeValue = typeSelect.value;
            if (typeValue === 'info') {
                typeSelect.classList.add('border-info');
            } else if (typeValue === 'success') {
                typeSelect.classList.add('border-success');
            } else if (typeValue === 'warning') {
                typeSelect.classList.add('border-warning');
            } else if (typeValue === 'danger') {
                typeSelect.classList.add('border-danger');
            }
            
            // Initialiser la priorité
            const prioritySelect = document.getElementById('priority');
            const priorityValue = prioritySelect.value;
            if (priorityValue === 'high') {
                prioritySelect.classList.add('border-danger');
            } else if (priorityValue === 'normal') {
                prioritySelect.classList.add('border-primary');
            } else {
                prioritySelect.classList.add('border-info');
            }
        });
    </script>
</body>
</html>