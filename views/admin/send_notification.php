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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/admin_navbar.php'; ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-bell me-2"></i>Envoyer une Notification</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($success)): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="title" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="recipients" class="form-label">Destinataires</label>
                                <select class="form-select" id="recipients" name="recipients[]" multiple required>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?php echo htmlspecialchars($user['id']); ?>">
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Type de notification</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="info">Information</option>
                                    <option value="success">Succès</option>
                                    <option value="warning">Avertissement</option>
                                    <option value="error">Erreur</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="priority" class="form-label">Priorité</label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low">Basse</option>
                                    <option value="normal" selected>Normale</option>
                                    <option value="high">Haute</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer la notification
                                </button>
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
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html> 