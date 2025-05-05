<?php
require_once 'models/User.php';

$userModel = new User($pdo);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?page=dashboard&error=unauthorized');
    exit();
}

$success = false;
$error = '';
$user = null;
$userId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = (int)$_POST['user_id'];
} elseif (isset($_GET['id'])) {
    $userId = (int)$_GET['id'];
}

if ($userId) {
    $user = $userModel->findById($userId);
    if (!$user) {
        $error = 'Invalid user ID. User not found.';
    } elseif (empty($user['email'])) {
        $error = 'User data is incomplete (missing email). Cannot proceed with deletion.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete']) && $user) {
    if ($userModel->delete($userId)) {
        $redirectPage = isset($_GET['from']) && $_GET['from'] === 'list' ? 'list-profile' : 'dashboard';
        header("Location: index.php?page=$redirectPage&success=delete_success");
        exit();
    } else {
        $error = 'Failed to delete the user. This might be the last admin user.';
    }
}
?>

<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-danger text-white text-center py-4">
            <h2 class="mb-0">Delete User</h2>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
            <div class="alert alert-danger text-center animate__animated animate__shakeX">
                <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!$user): ?>
            <form method="post" class="mb-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="user_id" class="form-label text-primary">Enter User ID to Delete</label>
                        <input type="number" class="form-control" id="user_id" name="user_id"
                            value="<?php echo htmlspecialchars($_POST['user_id'] ?? ''); ?>" required>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Find User <i
                            class="fas fa-search"></i></button>
                    <a href="index.php?page=profile" class="btn btn-secondary btn-lg ms-3">Cancel <i
                            class="fas fa-times"></i></a>
                </div>
            </form>
            <?php else: ?>
            <div class="alert alert-warning text-center">
                <strong>Warning:</strong> You are about to delete the user
                <strong><?php echo htmlspecialchars($user['username'] ?? $user['email'] ?? 'Unknown User'); ?></strong>
                (ID: <?php echo htmlspecialchars((string)$user['id']); ?>). This action cannot be undone.
            </div>

            <form method="post" class="mb-4">
                <input type="hidden" name="confirm_delete" value="1">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars((string)$user['id']); ?>">
                <div class="text-center">
                    <button type="submit" class="btn btn-danger btn-lg">Confirm Delete <i
                            class="fas fa-trash"></i></button>
                    <a href="<?php echo isset($_GET['from']) && $_GET['from'] === 'list' ? 'index.php?page=list-profile' : 'index.php?page=profile'; ?>"
                        class="btn btn-secondary btn-lg ms-3">Cancel <i class="fas fa-times"></i></a>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 800px;
}

.card-header {
    border-radius: 10px 10px 0 0;
}

.alert {
    border-radius: 8px;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    transition: background-color 0.3s;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #c82333;
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