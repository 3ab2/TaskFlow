<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

$adminController = new AdminController();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    // Validation
    $errors = [];
    if (empty($username)) $errors[] = "Le nom d'utilisateur est requis";
    if (empty($email)) $errors[] = "L'email est requis";
    if (empty($password)) $errors[] = "Le mot de passe est requis";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format d'email invalide";

    if (empty($errors)) {
        try {
            $result = $adminController->addUser($username, $email, $password, $role);
            if ($result) {
                $success = "Utilisateur ajouté avec succès!";
            } else {
                $errors[] = "Erreur lors de l'ajout de l'utilisateur";
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
    <title>Ajouter un Utilisateur - Taskflow Admin</title>
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
            <div class="col-lg-7 col-md-9">
                <div class="card fade-in">
                    <div class="card-header text-white d-flex align-items-center">
                        <i class="fas fa-user-plus fa-fw fa-lg me-3"></i>
                        <h4 class="mb-0">Ajouter un nouvel utilisateur</h4>
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
                                <span><?php echo htmlspecialchars($success); ?></span>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-id-badge fa-fw me-1"></i> Nom d'utilisateur
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           placeholder="Nom d'utilisateur" required>
                                    <div class="invalid-feedback">Le nom d'utilisateur est requis</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope fa-fw me-1"></i> Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="exemple@domaine.com" required>
                                <div class="invalid-feedback">Un email valide est requis</div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock fa-fw me-1"></i> Mot de passe
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Mot de passe" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                    </button>
                                    <div class="invalid-feedback">Un mot de passe est requis</div>
                                </div>
                                <div class="form-text mt-2">
                                    <div class="password-strength">
                                        <small>Force du mot de passe: </small>
                                        <span class="badge bg-danger" id="passwordStrength">Faible</span>
                                    </div>
                                    <small>Doit contenir au moins 8 caractères, dont des lettres et des chiffres</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-user-shield fa-fw me-1"></i> Rôle
                                </label>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-check p-3 border rounded">
                                            <input class="form-check-input" type="radio" name="role" id="roleUser" value="user" checked>
                                            <label class="form-check-label" for="roleUser">
                                                <i class="fas fa-user me-2 text-info"></i> Utilisateur
                                            </label>
                                            <div class="form-text">Accès limité au système</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check p-3 border rounded">
                                            <input class="form-check-input" type="radio" name="role" id="roleAdmin" value="admin">
                                            <label class="form-check-label" for="roleAdmin">
                                                <i class="fas fa-user-cog me-2 text-warning"></i> Administrateur
                                            </label>
                                            <div class="form-text">Accès complet au système</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Créer l'utilisateur
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
        
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
        
        // Check password strength
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBadge = document.getElementById('passwordStrength');
            
            // Définir les critères de force du mot de passe
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumbers = /\d/.test(password);
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            const length = password.length;
            
            let strength = 0;
            if (length >= 8) strength++;
            if (hasUpperCase && hasLowerCase) strength++;
            if (hasNumbers) strength++;
            if (hasSpecialChar) strength++;
            
            // Mettre à jour l'indicateur
            if (strength === 0 || length === 0) {
                strengthBadge.textContent = 'Faible';
                strengthBadge.className = 'badge bg-danger';
            } else if (strength === 1) {
                strengthBadge.textContent = 'Faible';
                strengthBadge.className = 'badge bg-danger';
            } else if (strength === 2) {
                strengthBadge.textContent = 'Moyen';
                strengthBadge.className = 'badge bg-warning';
            } else if (strength === 3) {
                strengthBadge.textContent = 'Bon';
                strengthBadge.className = 'badge bg-info';
            } else {
                strengthBadge.textContent = 'Excellent';
                strengthBadge.className = 'badge bg-success';
            }
        });
    </script>
</body>
</html>