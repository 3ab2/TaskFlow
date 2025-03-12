document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire pour le bouton "Ajouter tâche"
    const addTaskBtn = document.getElementById('addTaskBtn');
    const taskModal = new bootstrap.Modal(document.getElementById('taskModal'));
    const taskForm = document.getElementById('taskForm');

    if (addTaskBtn) {
        addTaskBtn.addEventListener('click', () => {
            document.getElementById('taskId').value = '';
            taskForm.reset();
            taskModal.show();
        });
    }

    // Soumission du formulaire de tâche
    if (taskForm) {
        taskForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(taskForm);
            const taskData = {
                title: formData.get('title'),
                description: formData.get('description'),
                priority: formData.get('priority'),
                status: formData.get('status'),
                deadline: formData.get('deadline')
            };

            const taskId = document.getElementById('taskId').value;
            const method = taskId ? 'PUT' : 'POST';
            const url = taskId ? `/pfe/api/tasks.php?id=${taskId}` : '/pfe/api/tasks.php';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(taskData)
                });

                const result = await response.json();

                if (result.success) {
                    showNotification(taskId ? 'Tâche mise à jour avec succès' : 'Tâche créée avec succès', 'success');
                    taskModal.hide();
                    loadTasks();
                } else {
                    showNotification(result.message || 'Une erreur est survenue', 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showNotification('Une erreur est survenue lors de la communication avec le serveur', 'error');
            }
        });
    }

    // Chargement des tâches
    async function loadTasks() {
        try {
            const response = await fetch('/pfe/api/tasks.php');
            const result = await response.json();

            if (result.success) {
                updateTaskColumns(result.data);
            } else {
                showNotification('Erreur lors du chargement des tâches', 'error');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showNotification('Erreur de communication avec le serveur', 'error');
        }
    }

    // Mise à jour des colonnes de tâches
    function updateTaskColumns(tasks) {
        const columns = {
            'to_do': document.getElementById('todoTasks'),
            'in_progress': document.getElementById('inProgressTasks'),
            'completed': document.getElementById('completedTasks')
        };

        // Vider les colonnes
        Object.values(columns).forEach(column => {
            if (column) column.innerHTML = '';
        });

        // Remplir les colonnes avec les tâches
        tasks.forEach(task => {
            const column = columns[task.status];
            if (column) {
                column.appendChild(createTaskCard(task));
            }
        });

        // Initialiser le drag & drop
        initializeDragAndDrop();
    }

    // Création d'une carte de tâche
    function createTaskCard(task) {
        const card = document.createElement('div');
        card.className = `task-card priority-${task.priority}`;
        card.setAttribute('draggable', 'true');
        card.setAttribute('data-task-id', task.id);

        const deadline = new Date(task.deadline).toLocaleDateString('fr-FR');
        
        card.innerHTML = `
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="mb-0">${escapeHtml(task.title)}</h6>
                <div class="dropdown">
                    <button class="btn btn-sm btn-link" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item edit-task" href="#" data-task-id="${task.id}">
                            <i class="fas fa-edit"></i> Modifier
                        </a></li>
                        <li><a class="dropdown-item delete-task" href="#" data-task-id="${task.id}">
                            <i class="fas fa-trash"></i> Supprimer
                        </a></li>
                    </ul>
                </div>
            </div>
            <p class="small mb-2">${escapeHtml(task.description || '')}</p>
            <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-${getPriorityColor(task.priority)}">${getPriorityLabel(task.priority)}</span>
                <small class="text-muted"><i class="far fa-calendar"></i> ${deadline}</small>
            </div>
        `;

        // Gestionnaires d'événements pour les actions
        const editBtn = card.querySelector('.edit-task');
        const deleteBtn = card.querySelector('.delete-task');

        editBtn.addEventListener('click', (e) => {
            e.preventDefault();
            editTask(task);
        });

        deleteBtn.addEventListener('click', (e) => {
            e.preventDefault();
            deleteTask(task.id);
        });

        return card;
    }

    // Initialisation du drag & drop
    function initializeDragAndDrop() {
        const draggables = document.querySelectorAll('.task-card');
        const dropzones = document.querySelectorAll('.task-column');

        draggables.forEach(draggable => {
            draggable.addEventListener('dragstart', () => {
                draggable.classList.add('dragging');
            });

            draggable.addEventListener('dragend', () => {
                draggable.classList.remove('dragging');
            });
        });

        dropzones.forEach(dropzone => {
            dropzone.addEventListener('dragover', e => {
                e.preventDefault();
                dropzone.classList.add('drag-over');
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('drag-over');
            });

            dropzone.addEventListener('drop', async (e) => {
                e.preventDefault();
                dropzone.classList.remove('drag-over');

                const draggable = document.querySelector('.dragging');
                if (!draggable) return;

                const taskId = draggable.getAttribute('data-task-id');
                const newStatus = dropzone.getAttribute('data-status');

                try {
                    const response = await fetch(`/pfe/api/tasks.php?id=${taskId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ status: newStatus })
                    });

                    const result = await response.json();

                    if (result.success) {
                        dropzone.appendChild(draggable);
                        showNotification('Statut de la tâche mis à jour', 'success');
                    } else {
                        showNotification(result.message || 'Erreur lors de la mise à jour du statut', 'error');
                        loadTasks(); // Recharger l'état initial
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    showNotification('Erreur de communication avec le serveur', 'error');
                    loadTasks(); // Recharger l'état initial
                }
            });
        });
    }

    // Fonctions utilitaires
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function getPriorityColor(priority) {
        const colors = {
            'high': 'danger',
            'medium': 'warning',
            'low': 'success'
        };
        return colors[priority] || 'secondary';
    }

    function getPriorityLabel(priority) {
        const labels = {
            'high': 'Haute',
            'medium': 'Moyenne',
            'low': 'Basse'
        };
        return labels[priority] || priority;
    }

    // Édition d'une tâche
    function editTask(task) {
        document.getElementById('taskId').value = task.id;
        document.getElementById('title').value = task.title;
        document.getElementById('description').value = task.description || '';
        document.getElementById('priority').value = task.priority;
        document.getElementById('status').value = task.status;
        document.getElementById('deadline').value = task.deadline.split('T')[0];
        
        taskModal.show();
    }

    // Suppression d'une tâche
    async function deleteTask(taskId) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
            return;
        }

        try {
            const response = await fetch(`/pfe/api/tasks.php?id=${taskId}`, {
                method: 'DELETE'
            });

            const result = await response.json();

            if (result.success) {
                showNotification('Tâche supprimée avec succès', 'success');
                loadTasks();
            } else {
                showNotification(result.message || 'Erreur lors de la suppression', 'error');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showNotification('Erreur de communication avec le serveur', 'error');
        }
    }

    // Fonction de notification
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
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        const container = document.getElementById('toastContainer') || document.body;
        container.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Charger les tâches au démarrage
    loadTasks();
});
