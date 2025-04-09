<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/pfe/assets/images/gdt.png" type="image/x-icon">
    <title>Réinitialiser le mot de passe - TaskFlow</title>
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <link rel="stylesheet" href="/pfe/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/dashboard.css">
</head>

<body>
    <?php include '../../includes/navbar.php';?>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="container" style="max-width: 700px;">
            <h2 class="text-center"><i class="fas fa-key me-2"></i>Réinitialiser le mot de passe</h2>
            <p class="text-center">Entrez votre adresse e-mail pour recevoir un lien de réinitialisation de mot de passe.</p>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm m-3">
                <div class="card-body">
                    <form action="/pfe/controllers/process_reset_password.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-5"></div> 

                        <button type="submit" class="btn btn-primary w-100 mb-3"><i class="fas fa-paper-plane me-2"></i>Envoyer le lien de réinitialisation</button>
                    </form>
                </div>
            </div>

            <div class="text-center">
                <p class="mb-0">Retour à la <a href="login.php" class="text-primary"><i class="fas fa-sign-in-alt me-2"></i>page de connexion</a></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/pfe/assets/js/main.js"></script>
    <script src="/pfe/assets/js/tasks.js"></script>
</body>

