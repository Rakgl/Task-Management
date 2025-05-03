<?php
require_once 'models/Task.php';

$taskModel = new Task($pdo);

// Get the task ID from the URL
$task_id = $_GET['id'] ?? null;
if (!$task_id || !is_numeric($task_id)) {
    header('Location: index.php?page=tasks');
    exit();
}

// Fetch the task
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->execute([$task_id, $_SESSION['user_id']]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    header('Location: index.php?page=tasks');
    exit();
}
?>
<div class="card" style="width: 100%;">
    <div class="card-body">

        <div class="col-md-12">
            <h2>Task Details</h2>
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($task['title']); ?></h5>
                </div>
                <div class="card-body">
                    <p><strong>Description:</strong>
                        <?php echo htmlspecialchars($task['description'] ?? 'No description'); ?>
                    </p>
                    <p><strong>Status:</strong>
                        <span
                            class="badge <?php echo $task['status'] === 'completed' ? 'bg-success' : 'bg-primary'; ?>">
                            <?php echo htmlspecialchars($task['status']); ?>
                        </span>
                    </p>
                    <p><strong>Created At:</strong> <?php echo htmlspecialchars($task['created_at']); ?></p>
                </div>
                <div class="card-footer">
                    <a href="index.php?page=task-edit&id=<?php echo $task['id']; ?>" class="btn btn-primary">Edit</a>
                    <a href="index.php?page=delete&id=<?php echo $task['id']; ?>" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                    <a href="index.php?page=tasks" class="btn btn-secondary">Back to Tasks</a>
                </div>
            </div>
        </div>
    </div>
</div>