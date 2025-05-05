<?php
require_once 'models/Task.php';

$taskModel = new Task($pdo);

// Fetch user's tasks
$tasks = $taskModel->getTasksByUserId($_SESSION['user_id']);
?>
<div class="card" style="width: 100%">
    <div class="card-body">
        <div class="col-md-12">
            <h2>Tasks</h2>
            <div class="mb-3">
                <a href="index.php?page=task-create" class="btn btn-primary">Create New Task</a>
            </div>

            <?php if (empty($tasks)): ?>
            <div class="alert alert-primary" role="alert">
                You have no tasks yet. <a href="index.php?page=task-create" class="alert-link text-primary">Create a
                    task</a> to
                get started!
            </div>
            <?php else: ?>
            <table class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Id</th>
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
                        <td><?php echo htmlspecialchars($task['id']); ?></td>
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
                            <a href="index.php?page=task-edit&id=<?php echo $task['id']; ?>"
                                class="btn btn-sm btn-outline-primary"
                                onclick="return confirm('Are you sure you want to edit this task?');">Edit</a>
                            <a href="index.php?page=task-delete&id=<?php echo $task['id']; ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>