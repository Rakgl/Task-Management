<?php
// Load models
require_once 'models/User.php';
require_once 'models/Task.php';

$userModel = new User($pdo);
$taskModel = new Task($pdo);

// Fetch user details using user_id
$user_id = $_SESSION['user_id'];
$user = $userModel->findById($user_id);
$username = $user['email'] ?? 'User'; 

$tasks = $taskModel->getTasksByUserId($user_id);
?>

<div class="card" style="width: 100%;">
    <div class="card-body">
        <h5 class="card-title">Your Dashboard</h5>
        <p class="card-text">Here’s an overview of your tasks.</p>

        <?php if (empty($tasks)): ?>
        <div class="alert alert-primary" role="alert">
            You have no tasks yet. <a href="index.php?page=task-create" class="alert-link text-primary">Create a
                task</a> to get started!
        </div>
        <?php else: ?>
        <table class="table table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
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
                    <td>
                        <a href="index.php?page=task-view&id=<?php echo $task['id']; ?>"
                            class="btn btn-sm btn-primary">View</a>
                        <a href="index.php?page=edit&id=<?php echo $task['id']; ?>"
                            class="btn btn-sm btn-outline-primary">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<style>
</style>