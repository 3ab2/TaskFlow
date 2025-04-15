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

// Définir la date du jour et la date minimale pour le sélecteur de date
$today = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Tâche - Taskflow Admin</title>
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
                        <i class="fas fa-clipboard-list fa-fw fa-lg me-3"></i>
                        <h4 class="mb-0">Ajouter une nouvelle tâche</h4>
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
                                           placeholder="Nom de la tâche" required>
                                    <div class="invalid-feedback">Le titre est requis</div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="priority" class="form-label">
                                        <i class="fas fa-flag fa-fw me-1"></i> Priorité
                                    </label>
                                    <select class="form-select" id="priority" name="priority" required>
                                        <option value="low">Basse</option>
                                        <option value="medium" selected>Moyenne</option>
                                        <option value="high">Haute</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left fa-fw me-1"></i> Description
                                </label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="4" placeholder="Détails de la tâche" required></textarea>
                                <div class="invalid-feedback">La description est requise</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="assigned_to" class="form-label">
                                        <i class="fas fa-user-check fa-fw me-1"></i> Assigné à
                                    </label>
                                    <select class="form-select" id="assigned_to" name="assigned_to" required>
                                        <option value="">Sélectionner un utilisateur</option>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?php echo htmlspecialchars($user['id']); ?>">
                                                <?php echo htmlspecialchars($user['username']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Veuillez sélectionner un utilisateur</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="due_date" class="form-label">
                                        <i class="fas fa-calendar-alt fa-fw me-1"></i> Date d'échéance
                                    </label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" 
                                           min="<?php echo $today; ?>" required>
                                    <div class="invalid-feedback">La date d'échéance est requise</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label">
                                    <i class="fas fa-tasks fa-fw me-1"></i> Statut
                                </label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending" selected>En attente</option>
                                    <option value="in_progress">En cours</option>
                                    <option value="completed">Terminée</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Créer la tâche
                                </button>
                                <a href="/pfe/views/admin/dashboard.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
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
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
        
        // Mise en évidence de la priorité sélectionnée
        document.getElementById('priority').addEventListener('change', function() {
            const prioritySelect = this;
            const selectedValue = prioritySelect.value;
            
            // Reset classes
            prioritySelect.classList.remove('border-danger', 'border-warning', 'border-info');
            
            // Add appropriate class based on selection
            if (selectedValue === 'high') {
                prioritySelect.classList.add('border-danger');
            } else if (selectedValue === 'medium') {
                prioritySelect.classList.add('border-warning');
            } else {
                prioritySelect.classList.add('border-info');
            }
        });
    </script>
</body>
</html>