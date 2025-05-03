<?php
// Load models
require_once 'models/User.php';
require_once 'models/Task.php';

$userModel = new User($pdo);
$taskModel = new Task($pdo);

$user_id = $_SESSION['user_id'];
$user = $userModel->findById($user_id);
$username = $user['email'] ?? 'User'; 

$admin_tasks = $taskModel->getTasksByUserId(1);
?>

<div class="card" style="width: 100%;">
    <div class="card-body">
        <h5 class="card-title">Your Dashboard</h5>
        <p class="card-text">Here’s an overview of the admin’s tasks (read-only).</p>

        <div class="alert alert-info" role="alert">
            This dashboard is read-only. No actions (e.g., create, edit, or view) are available to any user. It displays
            the admin's tasks (User ID 1).
        </div>

        <?php if (empty($admin_tasks)): ?>
        <div class="alert alert-primary" role="alert">
            The admin has no tasks yet.
        </div>
        <?php else: ?>
        <table class="table table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <?php if ($_SESSION['user_id'] == 1): ?>
                    <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admin_tasks as $task): ?>
                <tr>
                    <td><?php echo htmlspecialchars($task['title']); ?></td>
                    <td><?php echo htmlspecialchars($task['description'] ?? 'No description'); ?></td>
                    <td>
                        <span
                            class="badge <?php echo $task['status'] === 'completed' ? 'bg-success' : 'bg-primary'; ?>">
                            <?php echo htmlspecialchars($task['status']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($task['created_at']); ?></td>
                    <?php if ($_SESSION['user_id'] == 1): ?>
                    <td>
                        <a href="index.php?page=task-view&id=<?php echo $task['id']; ?>"
                            class="btn btn-sm btn-primary">View</a>
                        <a href="index.php?page=task-edit&id=<?php echo $task['id']; ?>"
                            class="btn btn-sm btn-outline-primary">Edit</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<style>
</style>