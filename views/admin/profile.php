<?php
require_once __DIR__ . '/../../controllers/AdminController.php';
$admin = new AdminController();

if (!$admin->isAdmin()) {
    header('Location: /pfe/views/auth/login.php');
    exit();
}

$adminInfo = $admin->getAdminInfo();
$recentActivity = $admin->getAdminActivity();
$preferences = $admin->getAdminPreferences();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/pfe/assets/images/admin_logo.png" type="image/x-icon">
    <title>Taskflow Admin - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="/pfe/assets/css/taskflow-custom.css" rel="stylesheet">
    <?php include '../includes/admin_style.php'; ?>
</head>

<body>
    <?php include '../includes/admin_navbar.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <!-- Menu de navigation -->
            <div class="col-md-3">
                <div class="card fade-in">
                    <div class="card-body">
                        <div class="list-group">
                            <a href="#personal-info" class="list-group-item list-group-item-action active"
                                data-bs-toggle="list">
                                <i class="fas fa-user me-2"></i>Informations
                            </a>
                            <a href="#activity-history" class="list-group-item list-group-item-action"
                                data-bs-toggle="list">
                                <i class="fas fa-history me-2"></i>Historique
                            </a>
                           
                            <a href="/pfe/controllers/logout.php"
                                class="list-group-item list-group-item-action text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu -->
            <div class="col-md-9">
                <div class="tab-content">
                    <!-- Informations Personnelles -->
                    <div class="tab-pane fade show active fade-in" id="personal-info">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 text-primary">
                                    <i class="fas fa-user me-2"></i>Informations Personnelles
                                </h5>
                            </div>
                            <div class="card-body">
                                <form id="profileForm">
                                    <div class="mb-3">
                                        <label class="form-label">Nom d'utilisateur</label>
                                        <input type="text" class="form-control" name="username"
                                            value="<?php echo htmlspecialchars($adminInfo['username']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email"
                                            value="<?php echo htmlspecialchars($adminInfo['email']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nouveau mot de passe</label>
                                        <input type="password" class="form-control" name="new_password">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirmer le mot de passe</label>
                                        <input type="password" class="form-control" name="confirm_password">
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Enregistrer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Historique des Activités -->
                    <div class="tab-pane fade fade-in" id="activity-history">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-primary">
                                    <i class="fas fa-history me-2"></i>Historique des Activités
                                </h5>
                                <button class="btn btn-danger btn-sm" id="clearHistory">
                                    <i class="fas fa-trash me-2"></i>Effacer tout
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Action</th>
                                                <th>Détails</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentActivity as $activity): ?>
                                                <tr>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($activity['created_at'])); ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($activity['action']); ?></td>
                                                    <td><?php echo htmlspecialchars($activity['details']); ?></td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm delete-activity"
                                                            data-id="<?php echo $activity['id']; ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($recentActivity)): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">Aucune activité récente</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <footer class="page-footer " style="margin-top: 100px;">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-1">© <?php echo date('Y'); ?> Taskflow - Tous droits réservés</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // Gestion du formulaire de profil
            $('#profileForm').on('submit', function (e) {
                e.preventDefault();
                const $submitBtn = $(this).find('button[type="submit"]');
                const originalText = $submitBtn.html();

                $submitBtn.prop('disabled', true).html('<span class="spinner me-2"></span>Enregistrement...');

                $.ajax({
                    url: '/pfe/api/admin/update_profile.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            showAlert('success', 'Profil mis à jour avec succès !');
                            $submitBtn.html('<i class="fas fa-check me-2"></i>Enregistré !');
                            setTimeout(() => {
                                $submitBtn.html(originalText);
                            }, 2000);
                        } else {
                            showAlert('danger', response.message || 'Erreur lors de l\'enregistrement');
                        }
                    },
                    error: function () {
                        showAlert('danger', 'Erreur lors de l\'enregistrement');
                    },
                    complete: function () {
                        $submitBtn.prop('disabled', false);
                    }
                });
            });

            // Gestion du formulaire des paramètres
            $('#settingsForm').on('submit', function (e) {
                e.preventDefault();
                const $submitBtn = $(this).find('button[type="submit"]');
                const originalText = $submitBtn.html();

                $submitBtn.prop('disabled', true).html('<span class="spinner me-2"></span>Enregistrement...');

                $.ajax({
                    url: '/pfe/api/admin/update_settings.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            showAlert('success', 'Paramètres enregistrés avec succès !');
                            $submitBtn.html('<i class="fas fa-check me-2"></i>Enregistré !');
                            setTimeout(() => {
                                $submitBtn.html(originalText);
                            }, 2000);
                        } else {
                            showAlert('danger', response.message || 'Erreur lors de l\'enregistrement des paramètres');
                        }
                    },
                    error: function () {
                        showAlert('danger', 'Erreur lors de l\'enregistrement des paramètres');
                    },
                    complete: function () {
                        $submitBtn.prop('disabled', false);
                    }
                });
            });

            // Suppression d'une activité
            $('.delete-activity').on('click', function () {
                const activityId = $(this).data('id');
                if (confirm('Êtes-vous sûr de vouloir supprimer cette activité ?')) {
                    const $row = $(this).closest('tr');
                    $.ajax({
                        url: '/pfe/api/admin/delete_activity.php',
                        method: 'POST',
                        data: { activity_id: activityId },
                        success: function (response) {
                            if (response.success) {
                                $row.fadeOut(300, function () {
                                    $(this).remove();
                                    if ($('tbody tr').length === 0) {
                                        $('tbody').append('<tr><td colspan="4" class="text-center">Aucune activité récente</td></tr>');
                                    }
                                });
                                showAlert('success', 'Activité supprimée avec succès !');
                            } else {
                                showAlert('danger', response.message || 'Erreur lors de la suppression');
                            }
                        }
                    });
                }
            });

            // Effacer tout l'historique
            $('#clearHistory').on('click', function () {
                if (confirm('Êtes-vous sûr de vouloir effacer tout l\'historique ?')) {
                    $.ajax({
                        url: '/pfe/api/admin/clear_activity.php',
                        method: 'POST',
                        success: function (response) {
                            if (response.success) {
                                $('tbody tr').fadeOut(300, function () {
                                    $('tbody').html('<tr><td colspan="4" class="text-center">Aucune activité récente</td></tr>');
                                });
                                showAlert('success', 'Historique effacé avec succès !');
                            } else {
                                showAlert('danger', response.message || 'Erreur lors de l\'effacement');
                            }
                        }
                    });
                }
            });

            // Fonction pour afficher les alertes
            function showAlert(type, message) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                $('.tab-pane.active').prepend(alertHtml);

                setTimeout(function () {
                    $('.alert').alert('close');
                }, 5000);
            }
        });
    </script>
</body>

</html>