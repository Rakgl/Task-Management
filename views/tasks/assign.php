<?php
require_once 'models/User.php';
require_once 'models/Task.php';

$userModel = new User($pdo);
$taskModel = new Task($pdo);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?page=dashboard&error=unauthorized');
    exit();
}

$users = $userModel->findAll();

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'status' => $_POST['status'] ?? 'pending',
        'assigned_to' => $_POST['assigned_to'] ? (int)$_POST['assigned_to'] : null,
        'created_by' => $_SESSION['user_id']
    ];

    if (empty($data['title']) || !$data['assigned_to']) {
        $error = 'Task title and assigned user are required.';
    } elseif ($taskModel->create($data)) {
        $success = true;
    } else {
        $error = 'Failed to assign task. Please try again.';
    }
}
?>

<div style="width: 100%;">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0">Assign Task</h2>
        </div>
        <div class="card-body">
            <?php if ($success): ?>
            <div class="alert alert-success text-center animate__animated animate__fadeIn">Task assigned successfully!
            </div>
            <?php elseif ($error): ?>
            <div class="alert alert-danger text-center animate__animated animate__shakeX">
                <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="title" class="form-label text-primary">Task Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label text-primary">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label text-primary">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="assigned_to" class="form-label text-primary">Assign To</label>
                        <select class="form-control" id="assigned_to" name="assigned_to" required>
                            <option value="">Select User</option>
                            <?php foreach ($users as $user): ?>
                            <option value="<?php echo htmlspecialchars($user['id']); ?>">
                                <?php echo htmlspecialchars($user['username']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <a href="index.php?page=dashboard" class="btn btn-secondary btn-lg">Back to Dashboard <i
                            class="fas fa-arrow-left"></i></a>
                    <button type="submit" class="btn btn-primary btn-lg">Assign Task <i
                            class="fas fa-save"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card-header {
    border-radius: 10px 10px 0 0;
}

.alert {
    border-radius: 8px;
}

.btn-primary {
    background-color: #3498db;
    border-color: #3498db;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    transition: background-color 0.3s;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #5a6268;
}

.card {
    border-radius: 10px;
}

.card-body {
    padding: 20px;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
}

.animate__animated {
    animation-duration: 1s;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.js"></script>