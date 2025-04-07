<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /pfe/views/dashboard.php');
    exit();

}

if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
    header('Location: /pfe/admin/dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - TaskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <style>
        .login-container {
            min-height: calc(100vh - 200px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px var(--shadow);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header i {
            font-size: 3rem;
            color: #4f46e5;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <?php include '../../includes/navbar.php'; ?>

    <div class="login-container mt-5">
        <div class="card login-card">
            <div class="login-header">
                <i class="fas fa-user-circle"></i>
                <h2>Connexion</h2>
                <p class="text-muted">Connectez-vous à votre compte TaskFlow</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            </form>

            <form action="/pfe/controllers/process_login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Me rappeler</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                </button>

                <div class="text-center">
                    <p class="mb-0">
                        <a href="reset_password.php" class="text-primary">Mot de passe oublié ?</a>
                    </p>
                </div><br>

                <div class="text-center">
                    <p class="mb-0">Vous n'avez pas de compte ?</p>
                    <a href="register.php" class="text-primary">Créer un compte</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>