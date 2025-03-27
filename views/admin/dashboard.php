<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

$admin = new AdminController();
if (!$admin->isAdmin()) {
    header('Location: /pfe/views/auth/login.php');
    exit();
}

$users = $admin->getAllUsers();
$tasks = $admin->getAllTasks();
$messages = $admin->getAllMessages();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskflow Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <?php include '../includes/admin_style.php'; ?>

    <style>
        ul.nav-tabs {
            border-bottom: none;
            border-radius: 10px;
            background-color: #333;
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }

        ul.nav-tabs li.nav-item {
            margin-right: 10px;
            
            
        }

        ul.nav-tabs li.nav-item a.nav-link {
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        
    </style>
</head>
<body>
    <?php include '../includes/admin_navbar.php'; ?>

    <div class="container mt-4">
        <ul class="nav nav-tabs" id="adminTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="users-tab" data-bs-toggle="tab" href="#users" role="tab" style="color:#000;">
                    <i class="fas fa-user me-2"></i>Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tasks-tab" data-bs-toggle="tab" href="#tasks" role="tab" style="color:#000;"><i class="fas fa-tasks me-2"></i>Tasks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="messages-tab" data-bs-toggle="tab" href="#messages" role="tab" style="color:#000;"><i class="fas fa-envelope me-2"></i> Messages</a>
            </li>
        </ul>

        <div class="tab-content mt-3" id="adminTabContent">
            <!-- Users Tab -->
            <div class="tab-pane fade show active" id="users" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">User Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="userSearch" placeholder="Rechercher un utilisateur par ID, nom d'utilisateur ou email...">
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td>
                                            <span class="editable" 
                                                  data-type="text" 
                                                  data-id="<?php echo $user['id']; ?>"
                                                  data-field="username">
                                                <?php echo htmlspecialchars($user['username']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="editable" 
                                                  data-type="email" 
                                                  data-id="<?php echo $user['id']; ?>"
                                                  data-field="email">
                                                <?php echo htmlspecialchars($user['email']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm role-select" 
                                                    data-id="<?php echo $user['id']; ?>">
                                                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>Utilisateur</option>
                                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger delete-user" data-id="<?php echo $user['id']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks Tab -->
            <div class="tab-pane fade" id="tasks" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Task Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="taskSearch" placeholder="Rechercher une tâche par ID, titre ou utilisateur...">
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titre</th>
                                        <th>Description</th>
                                        <th>Nom</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <td><?php echo $task['id']; ?></td>
                                        <td>
                                            <span class="editable" 
                                                  data-type="text" 
                                                  data-id="<?php echo $task['id']; ?>"
                                                  data-field="title">
                                                <?php echo htmlspecialchars($task['title']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="editable" 
                                                  data-type="textarea" 
                                                  data-id="<?php echo $task['id']; ?>"
                                                  data-field="description">
                                                <?php echo htmlspecialchars($task['description']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span>
                                                <?php echo htmlspecialchars($task['username']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm status-select" 
                                                    data-id="<?php echo $task['id']; ?>">
                                                <option value="pending" <?php echo $task['status'] === 'pending' ? 'selected' : ''; ?>>En attente</option>
                                                <option value="in_progress" <?php echo $task['status'] === 'in_progress' ? 'selected' : ''; ?>>En cours</option>
                                                <option value="completed" <?php echo $task['status'] === 'completed' ? 'selected' : ''; ?>>Terminée</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger delete-task" data-id="<?php echo $task['id']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Tab -->
            <div class="tab-pane fade" id="messages" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Message Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="messageSearch" placeholder="Rechercher un message par ID, contenu ou utilisateur...">
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Message</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($message['id']); ?></td>
                                    <td><?php echo htmlspecialchars($message['username']); ?></td>
                                    <td><?php echo htmlspecialchars($message['content']); ?></td>
                                    <td><?php echo htmlspecialchars($message['created_at']); ?></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-message" data-message-id="<?php echo $message['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/admin_footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#userSearch').on('keyup', function() {
                const searchText = $(this).val().toLowerCase();
                $('#users tbody tr').each(function() {
                    const id = $(this).find('td:eq(0)').text().toLowerCase();
                    const username = $(this).find('td:eq(1)').text().toLowerCase();
                    const email = $(this).find('td:eq(2)').text().toLowerCase();
                    
                    if (id.includes(searchText) || username.includes(searchText) || email.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('#taskSearch').on('keyup', function() {
                const searchText = $(this).val().toLowerCase();
                $('#tasks tbody tr').each(function() {
                    const id = $(this).find('td:eq(0)').text().toLowerCase();
                    const title = $(this).find('td:eq(1)').text().toLowerCase();
                    const username = $(this).find('td:eq(2)').text().toLowerCase();
                    
                    if (id.includes(searchText) || title.includes(searchText) || username.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('#messageSearch').on('keyup', function() {
                const searchText = $(this).val().toLowerCase();
                $('#messages tbody tr').each(function() {
                    const id = $(this).find('td:eq(0)').text().toLowerCase();
                    const username = $(this).find('td:eq(1)').text().toLowerCase();
                    const content = $(this).find('td:eq(2)').text().toLowerCase();
                    
                    if (id.includes(searchText) || username.includes(searchText) || content.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Role update
            $('.role-select').change(function() {
                const userId = $(this).data('user-id');
                const newRole = $(this).val();
                
                $.post('/pfe/api/admin/update_role.php', {
                    user_id: userId,
                    role: newRole
                }).done(function(response) {
                    alert('Role updated successfully');
                }).fail(function() {
                    alert('Failed to update role');
                });
            });

            // Delete user
            $('.delete-user').click(function() {
                if (confirm('Are you sure you want to delete this user?')) {
                    const userId = $(this).data('user-id');
                    
                    $.post('/pfe/api/admin/delete_user.php', {
                        user_id: userId
                    }).done(function() {
                        location.reload();
                    }).fail(function() {
                        alert('Failed to delete user');
                    });
                }
            });

            // Delete task
            $('.delete-task').click(function() {
                if (confirm('Are you sure you want to delete this task?')) {
                    const taskId = $(this).data('task-id');
                    
                    $.post('/pfe/api/admin/delete_task.php', {
                        task_id: taskId
                    }).done(function() {
                        location.reload();
                    }).fail(function() {
                        alert('Failed to delete task');
                    });
                }
            });

            // Delete message
            $('.delete-message').click(function() {
                if (confirm('Are you sure you want to delete this message?')) {
                    const messageId = $(this).data('message-id');
                    const $row = $(this).closest('tr');
                    
                    $.post('/pfe/api/admin/delete_message.php', {
                        message_id: messageId
                    })
                    .done(function() {
                        // Instead of reloading the page, just remove the row
                        $row.fadeOut(400, function() {
                            $(this).remove();
                        });
                    })
                    .fail(function() {
                        alert('Failed to delete message');
                    });
                }
            });
        });
    </script>
</body>
</html> 