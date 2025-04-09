<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/pfe/assets/images/gdt.png" type="image/x-icon">
    <title>Abonnement - TaskFlow</title>
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .message-container {
            text-align: center;
            margin-top: 20%;
            animation: fadeIn 2s ease-out;
        }
        
        .apology-icon {
            font-size: 4rem;
            color: #ff4757;
            animation: bounce 2s infinite;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-20px); }
            60% { transform: translateY(-10px); }
        }
    </style>
</head>

<body>
    <div class="message-container">
        <i class="fas fa-exclamation-circle apology-icon"></i>
        <h2>Nous nous excusons pour le désagrément</h2>
        <p>Actuellement, il n'y a pas de méthodes de paiement disponibles. Nous prévoyons d'ajouter des options supplémentaires à l'avenir.</p>
    </div>
    

    <script>
        setTimeout(function() {
            window.location.href = '/pfe/views/accueil.php';
        }, 20000);
    </script>
</body>

</html>

