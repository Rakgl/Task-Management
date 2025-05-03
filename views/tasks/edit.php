<?php
require_once 'models/Task.php';

$taskModel = new Task($pdo);

$task_id = $_GET['id'] ?? null;
if (!$task_id) {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? 'pending';

    if (empty($title)) {
        $error = "Title is required.";
    } else {
        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $description, $status, $task_id, $_SESSION['user_id']]);

        header('Location: index.php?page=tasks');
        exit();
    }
}
?>
<div class="card" style="width: 100%;">
    <div class="card-body">

        <div class="col-md-12">
            <h2>Edit Task</h2>
            <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="<?php echo htmlspecialchars($task['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"
                        rows="3"><?php echo htmlspecialchars($task['description'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="pending" <?php echo ($task['status'] === 'pending') ? 'selected' : ''; ?>>Pending
                        </option>
                        <option value="completed" <?php echo ($task['status'] === 'completed') ? 'selected' : ''; ?>>
                            Completed
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Task</button>
                <a href="index.php?page=tasks" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>