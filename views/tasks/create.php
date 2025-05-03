<?php
require_once 'models/Task.php';

$taskModel = new Task($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? 'pending';
    $user_id = $_SESSION['user_id'];

    // Basic validation
    if (empty($title)) {
        $error = "Title is required.";
    } else {
        // Save the task
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, status, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $title, $description, $status]);

        header('Location: index.php?page=dashboard');
        exit();
    }
}
?>

<div class="card" style="width: 100%;">
    <div class="card-body">

        <div class="col-md-12">
            <h2>Create Task</h2>
            <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"
                        rows="3"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="pending"
                            <?php echo (($_POST['status'] ?? 'pending') === 'pending') ? 'selected' : ''; ?>>Pending
                        </option>
                        <option value="completed"
                            <?php echo (($_POST['status'] ?? '') === 'completed') ? 'selected' : ''; ?>>
                            Completed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create Task</button>
                <a href="index.php?page=tasks" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

</div>