<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /pfe/views/auth/login.php');
    exit();
}

// Assuming the username is stored in $_SESSION['username']
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Utilisateur';

// Get redirect URL from GET parameter or default to accueil.php
$redirectUrl = isset($_GET['redirect']) ? $_GET['redirect'] : '/pfe/views/accueil.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Chargement - TaskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        
        .loading-container {
            text-align: center;
            padding: 2rem;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 90%;
            width: 500px;
        }
        
        .welcome-message {
            margin-bottom: 2rem;
            color: #3a3a3a;
        }
        
        .welcome-message h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .welcome-message p {
            font-size: 1.2rem;
            color: #6c757d;
        }
        
        .username {
            font-weight: bold;
            color: #0d6efd;
        }
        
        .spinner-container {
            margin: 2rem 0;
        }
        
        .spinner-grow {
            width: 1.5rem;
            height: 1.5rem;
            margin: 0 0.25rem;
        }
        
        .spinner-grow:nth-child(1) {
            animation-delay: 0s;
        }
        
        .spinner-grow:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .spinner-grow:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        .spinner-grow:nth-child(4) {
            animation-delay: 0.6s;
        }
        
        .loading-text {
            font-size: 1.1rem;
            color: #6c757d;
            margin-top: 1rem;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }
    </style>
</head>

<body>
    <div class="loading-container">
        <div class="welcome-message">
            <img src="/pfe/assets/images/gdt.png" alt="TaskFlow Logo" class="logo">
            <h1>Bienvenue sur TaskFlow</h1>
            <p>Bonjour <span class="username"><?php echo $username; ?></span>, votre espace est en cours de chargement</p>
        </div>

        <div class="spinner-container">
            <div class="spinner-grow text-primary" role="status"></div>
            <div class="spinner-grow text-primary" role="status"></div>
            <div class="spinner-grow text-primary" role="status"></div>
            <div class="spinner-grow text-primary" role="status"></div>
        </div>

        <p class="loading-text">Préparation de votre espace de travail...</p>
    </div>

    <script>
        // Redirect after a short delay
        setTimeout(function() {
            window.location.href = "<?php echo $redirectUrl; ?>";
        }, 5000); // 3 second delay before redirecting
    </script>
</body>

</html>