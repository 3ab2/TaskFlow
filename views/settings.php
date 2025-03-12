<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /pfe/views/auth/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - TaskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pfe/assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container py-5">
        <h2 class="mb-4">Paramètres</h2>
        
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                        <i class="fas fa-cog me-2"></i> Général
                    </a>
                    <a href="#appearance" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="fas fa-palette me-2"></i> Apparence
                    </a>
                    <a href="#language" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="fas fa-language me-2"></i> Langue
                    </a>
                    <a href="#notifications" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="fas fa-bell me-2"></i> Notifications
                    </a>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="general">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Paramètres généraux</h5>
                                <form id="generalSettingsForm">
                                    <div class="mb-3">
                                        <label class="form-label">Fuseau horaire</label>
                                        <select class="form-select" name="timezone">
                                            <option value="Africa/Casablanca">Casablanca</option>
                                            <option value="Europe/Paris">Paris</option>
                                            <option value="UTC">UTC</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Format de date</label>
                                        <select class="form-select" name="dateFormat">
                                            <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                                            <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                                            <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="appearance">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Apparence</h5>
                                <form id="appearanceSettingsForm">
                                    <div class="mb-3">
                                        <label class="form-label">Thème</label>
                                        <select class="form-select" name="theme" id="themeSelect">
                                            <option value="light">Clair</option>
                                            <option value="dark">Sombre</option>
                                            <option value="auto">Automatique</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Densité d'affichage</label>
                                        <select class="form-select" name="density">
                                            <option value="comfortable">Confortable</option>
                                            <option value="compact">Compact</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="language">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Langue</h5>
                                <form id="languageSettingsForm">
                                    <div class="mb-3">
                                        <label class="form-label">Langue de l'interface</label>
                                        <select class="form-select" name="language">
                                            <option value="fr">Français</option>
                                            <option value="en">English</option>
                                            <option value="ar">العربية</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="notifications">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Notifications</h5>
                                <form id="notificationSettingsForm">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="emailNotifications">
                                            <label class="form-check-label" for="emailNotifications">
                                                Notifications par email
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="pushNotifications">
                                            <label class="form-check-label" for="pushNotifications">
                                                Notifications push
                                            </label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Charger les paramètres sauvegardés
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.getElementById('themeSelect').value = savedTheme;
        document.body.setAttribute('data-theme', savedTheme);

        // Gérer le changement de thème
        document.getElementById('themeSelect').addEventListener('change', function(e) {
            const theme = e.target.value;
            document.body.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
        });

        // Gérer la soumission des formulaires
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                
                // Sauvegarder les paramètres
                Object.entries(data).forEach(([key, value]) => {
                    localStorage.setItem(key, value);
                });

                // Afficher un message de succès
                alert('Paramètres sauvegardés avec succès !');
            });
        });
    });
    </script>
</body>
</html>
