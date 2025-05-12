document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les sélecteurs de statut
    const statusSelects = document.querySelectorAll('.status-select');

    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const taskId = this.getAttribute('data-id');
            const newStatus = this.value;
            const selectElement = this;

            // Désactiver le sélecteur pendant la mise à jour
            selectElement.disabled = true;

            // Ajouter une classe de chargement
            selectElement.classList.add('updating');

            // Envoyer la requête AJAX
            fetch('/pfe/controllers/admin/update_task_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    task_id: taskId,
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Ajouter une classe de succès temporaire
                    selectElement.classList.remove('updating');
                    selectElement.classList.add('update-success');

                    // Mettre à jour l'interface utilisateur
                    const row = selectElement.closest('tr');
                    if (row) {
                        row.classList.add('updated');
                        setTimeout(() => row.classList.remove('updated'), 2000);
                    }

                    // Notification de succès
                    showNotification('Succès', 'Le statut de la tâche a été mis à jour avec succès', 'success');
                } else {
                    // En cas d'erreur, rétablir l'ancienne valeur
                    selectElement.value = selectElement.getAttribute('data-original-value');
                    showNotification('Erreur', 'Impossible de mettre à jour le statut de la tâche', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                // En cas d'erreur, rétablir l'ancienne valeur
                selectElement.value = selectElement.getAttribute('data-original-value');
                showNotification('Erreur', 'Une erreur est survenue lors de la mise à jour', 'error');
            })
            .finally(() => {
                // Réactiver le sélecteur
                selectElement.disabled = false;
                selectElement.classList.remove('updating');
                setTimeout(() => selectElement.classList.remove('update-success'), 2000);
            });
        });

        // Sauvegarder la valeur originale
        select.setAttribute('data-original-value', select.value);
    });

    // Fonction pour afficher les notifications
    function showNotification(title, message, type) {
        const notificationContainer = document.getElementById('notification-container') || createNotificationContainer();
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <strong>${title}</strong>
            <p>${message}</p>
        `;

        notificationContainer.appendChild(notification);

        // Supprimer la notification après 3 secondes
        setTimeout(() => {
            notification.remove();
            if (notificationContainer.children.length === 0) {
                notificationContainer.remove();
            }
        }, 3000);
    }

    // Fonction pour créer le conteneur de notifications
    function createNotificationContainer() {
        const container = document.createElement('div');
        container.id = 'notification-container';
        document.body.appendChild(container);
        return container;
    }
});