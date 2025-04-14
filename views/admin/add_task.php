<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

$adminController = new AdminController();

// Récupérer la liste des utilisateurs pour le select
$users = $adminController->getAllUsers();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $assigned_to = $_POST['assigned_to'] ?? '';
    $priority = $_POST['priority'] ?? 'medium';
    $due_date = $_POST['due_date'] ?? '';
    $status = $_POST['status'] ?? 'pending';

    // Validation
    $errors = [];
    if (empty($title)) $errors[] = "Le titre est requis";
    if (empty($description)) $errors[] = "La description est requise";
    if (empty($assigned_to)) $errors[] = "L'assignation est requise";
    if (empty($due_date)) $errors[] = "La date d'échéance est requise";

    if (empty($errors)) {
        try {
            $result = $adminController->addTask($title, $description, $assigned_to, $priority, $due_date, $status);
            if ($result) {
                $success = "Tâche ajoutée avec succès!";
            } else {
                $errors[] = "Erreur lors de l'ajout de la tâche";
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
    <title>Ajouter une Tâche - Taskflow Admin</title>
    <link rel="shortcut icon" href="/pfe/assets/images/admin_logo.png" type="image/x-icon">
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
                        <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Ajouter une Tâche</h4>
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
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Assigné à</label>
                                <select class="form-select" id="assigned_to" name="assigned_to" required>
                                    <option value="">Sélectionner un utilisateur</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?php echo htmlspecialchars($user['id']); ?>">
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="priority" class="form-label">Priorité</label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low">Basse</option>
                                    <option value="medium" selected>Moyenne</option>
                                    <option value="high">Haute</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="due_date" class="form-label">Date d'échéance</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" required>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending">En attente</option>
                                    <option value="in_progress">En cours</option>
                                    <option value="completed">Terminée</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Ajouter la tâche
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