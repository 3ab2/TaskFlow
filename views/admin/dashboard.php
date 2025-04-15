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
    <link rel="shortcut icon" href="/pfe/assets/images/admin_logo.png" type="image/x-icon">
    <title>Taskflow Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="/pfe/assets/css/taskflow-custom.css" rel="stylesheet">
    <?php include '../includes/admin_style.php'; ?>

    <style>
        /* Custom tab styles to match with taskflow-custom.css */
        ul.nav-tabs {
            border-bottom: none;
            border-radius: 10px;
            background-color: white;
            display: flex;
            justify-content: flex-start;
            padding: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        ul.nav-tabs li.nav-item {
            margin-right: 10px;
        }

        ul.nav-tabs li.nav-item a.nav-link {
            color: #555;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 20px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        ul.nav-tabs li.nav-item a.nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        ul.nav-tabs li.nav-item a.nav-link:hover:not(.active) {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }

        /* Status badge styles */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-pending {
            background-color: rgba(249, 199, 79, 0.2);
            color: #d1a429;
        }

        .status-in-progress {
            background-color: rgba(72, 149, 239, 0.2);
            color: #3a7dcc;
        }

        .status-completed {
            background-color: rgba(76, 201, 164, 0.2);
            color: #3ba788;
        }

        /* Editable field styles */
        .editable {
            padding: 3px 6px;
            border-radius: 4px;
            transition: all 0.2s;
            cursor: pointer;
        }

        .editable:hover {
            background-color: rgba(67, 97, 238, 0.1);
        }

        /* Table styles */
        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead th {
            background-color: rgba(67, 97, 238, 0.05);
            color: #555;
            font-weight: 600;
            border-top: none;
        }

        /* Action buttons */
        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            margin-right: 5px;
        }

        .card-header {

            background: rgb(78, 96, 177);

        }

        #adminTabs {
            background-color: rgba(255, 255, 255, 0.0);
        }

    </style>
</head>

<body>
    <?php include '../includes/admin_navbar.php'; ?>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card fade-in">
                    <div class="card-header">
                        
                       
                        <ul class="nav nav-tabs  d-flex align-items-center justify-content-around" id="adminTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="users-tab" data-bs-toggle="tab" href="#users" role="tab">
                                    <i class="fas fa-user me-2"></i>Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tasks-tab" data-bs-toggle="tab" href="#tasks" role="tab">
                                    <i class="fas fa-tasks me-2"></i>Tasks
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="messages-tab" data-bs-toggle="tab" href="#messages" role="tab">
                                    <i class="fas fa-envelope me-2"></i>Messages
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <h3 class="text-primary"><i
                                                class="fas fa-users me-2"></i><?php echo count($users); ?></h3>
                                        <h5>Total Users</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <h3 class="text-primary"><i
                                                class="fas fa-tasks me-2"></i><?php echo count($tasks); ?></h3>
                                        <h5>Total Tasks</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <h3 class="text-primary"><i
                                                class="fas fa-envelope me-2"></i><?php echo count($messages); ?></h3>
                                        <h5>Total Messages</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="tab-content mt-3" id="adminTabContent">
            <!-- Users Tab -->
            <div class="tab-pane fade show active fade-in" id="users" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0 text-white">User Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="userSearch"
                                    placeholder="Search user by ID, username or email...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td>
                                                <span class="editable" data-type="text" data-id="<?php echo $user['id']; ?>"
                                                    data-field="username">
                                                    <?php echo htmlspecialchars($user['username']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="editable" data-type="email"
                                                    data-id="<?php echo $user['id']; ?>" data-field="email">
                                                    <?php echo htmlspecialchars($user['email']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm role-select"
                                                    data-id="<?php echo $user['id']; ?>">
                                                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger delete-user"
                                                    data-user-id="<?php echo $user['id']; ?>">
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
            <div class="tab-pane fade fade-in" id="tasks" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0 text-white">Task Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="taskSearch"
                                    placeholder="Search task by ID, title or user...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>User</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tasks as $task): ?>
                                        <tr>
                                            <td><?php echo $task['id']; ?></td>
                                            <td>
                                                <span class="editable" data-type="text" data-id="<?php echo $task['id']; ?>"
                                                    data-field="title">
                                                    <?php echo htmlspecialchars($task['title']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="editable" data-type="textarea"
                                                    data-id="<?php echo $task['id']; ?>" data-field="description">
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
                                                    <option value="pending" <?php echo $task['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="in_progress" <?php echo $task['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                                    <option value="completed" <?php echo $task['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger delete-task"
                                                    data-task-id="<?php echo $task['id']; ?>">
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
            <div class="tab-pane fade fade-in" id="messages" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0 text-white">Message Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="messageSearch"
                                    placeholder="Search message by ID, content or user...">
                            </div>
                        </div>
                        <div class="table-responsive">
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
                                                <button class="btn btn-sm btn-danger delete-message"
                                                    data-message-id="<?php echo $message['id']; ?>">
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
    </div>

    <?php include __DIR__ . '/../includes/admin_footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Search functionality
            $('#userSearch').on('keyup', function () {
                const searchText = $(this).val().toLowerCase();
                $('#users tbody tr').each(function () {
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

            $('#taskSearch').on('keyup', function () {
                const searchText = $(this).val().toLowerCase();
                $('#tasks tbody tr').each(function () {
                    const id = $(this).find('td:eq(0)').text().toLowerCase();
                    const title = $(this).find('td:eq(1)').text().toLowerCase();
                    const username = $(this).find('td:eq(3)').text().toLowerCase();

                    if (id.includes(searchText) || title.includes(searchText) || username.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('#messageSearch').on('keyup', function () {
                const searchText = $(this).val().toLowerCase();
                $('#messages tbody tr').each(function () {
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
            $('.role-select').change(function () {
                const userId = $(this).data('id');
                const newRole = $(this).val();

                $.post('/pfe/api/admin/update_role.php', {
                    user_id: userId,
                    role: newRole
                })
                    .done(function (response) {
                        showToast('Success', 'Role updated successfully', 'success');
                    })
                    .fail(function () {
                        showToast('Error', 'Failed to update role', 'danger');
                    });
            });

            // Delete user
            $('.delete-user').click(function () {
                if (confirm('Are you sure you want to delete this user?')) {
                    const userId = $(this).data('user-id');
                    const $row = $(this).closest('tr');

                    $.post('/pfe/api/admin/delete_user.php', {
                        user_id: userId
                    })
                        .done(function (response) {
                            if (response.success) {
                                $row.fadeOut(400, function () {
                                    $(this).remove();
                                });
                                showToast('Success', 'User deleted successfully', 'success');
                            } else {
                                showToast('Error', 'Failed to delete user', 'danger');
                            }
                        })
                        .fail(function () {
                            showToast('Error', 'Failed to delete user', 'danger');
                        });
                }
            });

            // Delete task
            $('.delete-task').click(function () {
                if (confirm('Are you sure you want to delete this task?')) {
                    const taskId = $(this).data('task-id');
                    const $row = $(this).closest('tr');

                    $.post('/pfe/api/admin/delete_task.php', {
                        task_id: taskId
                    })
                        .done(function (response) {
                            if (response.success) {
                                $row.fadeOut(400, function () {
                                    $(this).remove();
                                });
                                showToast('Success', 'Task deleted successfully', 'success');
                            } else {
                                showToast('Error', 'Failed to delete task', 'danger');
                            }
                        })
                        .fail(function () {
                            showToast('Error', 'Failed to delete task', 'danger');
                        });
                }
            });

            // Delete message
            $('.delete-message').click(function () {
                if (confirm('Are you sure you want to delete this message?')) {
                    const messageId = $(this).data('message-id');
                    const $row = $(this).closest('tr');

                    $.post('/pfe/api/admin/delete_message.php', {
                        message_id: messageId
                    })
                        .done(function (response) {
                            if (response.success) {
                                $row.fadeOut(400, function () {
                                    $(this).remove();
                                });
                                showToast('Success', 'Message deleted successfully', 'success');
                            } else {
                                showToast('Error', 'Failed to delete message', 'danger');
                            }
                        })
                        .fail(function () {
                            showToast('Error', 'Failed to delete message', 'danger');
                        });
                }
            });

            // Editable fields
            $('.editable').click(function () {
                const currentValue = $(this).text().trim();
                const fieldType = $(this).data('type');
                const fieldId = $(this).data('id');
                const fieldName = $(this).data('field');

                if (fieldType === 'textarea') {
                    $(this).html(`<textarea class="form-control">${currentValue}</textarea>
                                 <div class="mt-2">
                                     <button class="btn btn-primary btn-sm save-edit">Save</button>
                                     <button class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
                                 </div>`);
                } else {
                    $(this).html(`<input type="${fieldType}" class="form-control" value="${currentValue}">
                                 <div class="mt-2">
                                     <button class="btn btn-primary btn-sm save-edit">Save</button>
                                     <button class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
                                 </div>`);
                }

                // Cancel edit
                $(this).find('.cancel-edit').click(function (e) {
                    e.stopPropagation();
                    $(this).closest('.editable').html(currentValue);
                });

                // Save edit
                $(this).find('.save-edit').click(function (e) {
                    e.stopPropagation();
                    const $parent = $(this).closest('.editable');
                    const newValue = fieldType === 'textarea' ?
                        $parent.find('textarea').val() :
                        $parent.find('input').val();

                    // AJAX call to update the value would go here
                    // For now, just update the display
                    $parent.html(newValue);

                    showToast('Success', `${fieldName} updated successfully`, 'success');
                });
            });

            // Toast notification function
            function showToast(title, message, type) {
                // Create toast container if it doesn't exist
                if ($('#toast-container').length === 0) {
                    $('body').append(`
                        <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
                    `);
                }

                // Create toast
                const toastId = 'toast-' + Date.now();
                const toast = `
                    <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
                        <div class="toast-header bg-${type} text-white">
                            <strong class="me-auto">${title}</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            ${message}
                        </div>
                    </div>
                `;

                // Append and show toast
                $('#toast-container').append(toast);
                const toastElement = new bootstrap.Toast(document.getElementById(toastId));
                toastElement.show();

                // Remove toast after it's hidden
                $(`#${toastId}`).on('hidden.bs.toast', function () {
                    $(this).remove();
                });
            }
        });
    </script>
</body>

</html>