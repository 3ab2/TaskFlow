<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - TaskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Réinitialiser le mot de passe</h2>
        <p class="text-center">Entrez votre adresse e-mail pour recevoir un lien de réinitialisation de mot de passe.
        </p>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <form action="/pfe/controllers/process_reset_password.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">Envoyer le lien de réinitialisation</button>
        </form>

        <div class="text-center">
            <p class="mb-0">Retour à la <a href="login.php" class="text-primary">page de connexion</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>