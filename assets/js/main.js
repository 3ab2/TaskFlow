document.addEventListener('DOMContentLoaded', function() {
    // Gestion du thème
    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle) return; // Exit if themeToggle does not exist

    const htmlElement = document.documentElement;
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Appliquer le thème initial
    htmlElement.setAttribute('data-theme', currentTheme);
    updateThemeIcon(currentTheme);
    
    themeToggle.addEventListener('click', function() {
        const currentTheme = htmlElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        htmlElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });
    
    function updateThemeIcon(theme) {
        const icon = themeToggle.querySelector('i');
        if (theme === 'dark') {
            icon.className = 'fas fa-sun';
            themeToggle.title = 'Passer en mode clair';
        } else {
            icon.className = 'fas fa-moon';
            themeToggle.title = 'Passer en mode sombre';
        }
    }
    
    // Gestion du statut utilisateur
    const statusDropdownItems = document.querySelectorAll('[data-status]');
    const statusButton = document.getElementById('statusDropdown');
    if (!statusButton) return; // Exit if statusButton does not exist

    
    if (statusDropdownItems && statusButton) {
        statusDropdownItems.forEach(item => {
            item.addEventListener('click', async (e) => {
                e.preventDefault();
                const newStatus = e.currentTarget.dataset.status;
                const statusIndicator = statusButton.querySelector('.status-indicator');
                
                try {
                    const response = await fetch('/pfe/api/update-status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ status: newStatus })
                    });

                    if (response.ok) {
                        statusIndicator.className = `status-indicator status-${newStatus}`;
                        const statusText = e.currentTarget.textContent.trim();
                        statusButton.innerHTML = `
                            <span class="status-indicator status-${newStatus}"></span>
                            ${statusText}
                        `;
                    }
                } catch (error) {
                    console.error('Erreur lors de la mise à jour du statut:', error);
                }
            });
        });
    }

    // Animations au défilement
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.card, .btn-lg, .display-4, .lead').forEach(el => {
        el.classList.add('opacity-0');
        observer.observe(el);
    });

    // Gestionnaire des tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Gestionnaire de déconnexion automatique après inactivité
    let inactivityTimer;
    const inactivityTimeout = 30 * 60 * 1000; // 30 minutes

    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(() => {
            showNotification('Vous serez déconnecté pour inactivité dans 1 minute.', 'warning');
            setTimeout(() => {
                window.location.href = '/controllers/logout.php';
            }, 60000);
        }, inactivityTimeout);
    }

    // Événements pour réinitialiser le timer d'inactivité
    ['mousemove', 'keypress', 'click', 'touchstart'].forEach(event => {
        document.addEventListener(event, resetInactivityTimer);
    });

    // Initialiser le timer
    resetInactivityTimer();

    // Gestionnaire des notifications
    function showNotification(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-danger me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }
});
