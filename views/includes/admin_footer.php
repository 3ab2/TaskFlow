<?php
$current_year = date('Y');
?>
<style>
    .admin-footer {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: #f3f4f6;
        padding: 2rem 0;
        margin-top: 3rem;
        position: relative;
        overflow: hidden;
    }

    .admin-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('/pfe/assets/images/pattern.svg') repeat;
        opacity: 0.05;
    }

    .footer-content {
        position: relative;
        z-index: 1;
    }

    .footer-section {
        margin-bottom: 1.5rem;
    }

    .footer-section h5 {
        color: #e9d5ff;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 0.5rem;
    }

    .footer-links a {
        color: #f3f4f6;
        text-decoration: none;
        opacity: 0.8;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .footer-links a:hover {
        opacity: 1;
        color: #e9d5ff;
        transform: translateX(5px);
    }

    .footer-links i {
        margin-right: 8px;
        width: 16px;
        text-align: center;
    }

    .footer-bottom {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
        font-size: 0.9rem;
        opacity: 0.7;
    }

    .social-links {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .social-links a {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f3f4f6;
        transition: all 0.3s ease;
    }

    .social-links a:hover {
        background: #e9d5ff;
        color: #1f2937;
        transform: translateY(-3px);
    }

    .footer-stats {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 0.5rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        flex: 1;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #e9d5ff;
    }

    .stat-label {
        font-size: 0.8rem;
        opacity: 0.7;
    }

    @media (max-width: 768px) {
        .footer-section {
            margin-bottom: 2rem;
        }

        .footer-stats {
            flex-direction: column;
        }

        .stat-item {
            margin-bottom: 0.5rem;
        }
    }
</style>

<footer class="admin-footer">
    <div class="container footer-content">
        <div class="row">
            <div class="col-md-4 footer-section">
                <h5>À propos de Taskflow</h5>
                <p style="opacity: 0.8; margin-bottom: 1rem;">
                    Une plateforme moderne de gestion de tâches conçue pour optimiser votre productivité et faciliter la collaboration d'équipe.
                </p>
                <div class="social-links">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" title="GitHub"><i class="fab fa-github"></i></a>
                </div>
            </div>
            <div class="col-md-4 footer-section">
                <h5>Liens Rapides</h5>
                <ul class="footer-links">
                    <li><a href="/pfe/views/admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="/pfe/views/admin/add_user.php"><i class="fas fa-user-plus"></i> Ajouter Utilisateur</a></li>
                    <li><a href="/pfe/views/admin/add_task.php"><i class="fas fa-plus-circle"></i> Nouvelle Tâche</a></li>
                    <li><a href="/pfe/views/admin/send_notification.php"><i class="fas fa-bell"></i> Notifications</a></li>
                </ul>
            </div>
            <div class="col-md-4 footer-section">
                <h5>Contact</h5>
                <ul class="footer-links">
                <li><i class="fas fa-map-marker-alt me-2"></i> Centre d'instruction des transmissions,kenitra </li>
                    <li><i class="fas fa-envelope me-2"></i> admintaskflow@gmail.com</li>             
                    <li><i class="fas fa-phone me-2"></i> +212 589 123 456</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo $current_year; ?> Taskflow Admin. Tous droits réservés.</p>
        </div>
    </div>
</footer> 