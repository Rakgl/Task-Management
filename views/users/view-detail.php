<?php
require_once './models/User.php';

$userModel = new User($pdo);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?page=dashboard&error=unauthorized');
    exit();
}

$userId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$user = $userId ? $userModel->findById($userId) : null;

if (!$user) {
    header('Location: index.php?page=list-profile&error=invalid_user');
    exit();
}
?>

<div style="width: 100%;">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-info text-white text-center py-4">
            <h2 class="mb-0">User Details</h2>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <?php if ($user['profile_picture']): ?>
                <img src="/uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture"
                    class="profile-img rounded-circle shadow-sm"
                    style="width: 150px; height: 150px; object-fit: cover;">
                <?php else: ?>
                <div class="profile-img-placeholder rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                    style="width: 150px; height: 150px; font-size: 24px;">No Image</div>
                <?php endif; ?>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label text-primary">ID</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($user['id']); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-primary">Username</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($user['username'] ?? 'Not set'); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-primary">Email</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-primary">Role</label>
                    <p class="form-control-static"><?php echo htmlspecialchars(ucfirst($user['role'])); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-primary">Phone</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($user['phone'] ?? 'Not set'); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-primary">Position</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($user['position'] ?? 'Not set'); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-primary">Birthday</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($user['birthday'] ?? 'Not set'); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-primary">Created At</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($user['created_at']); ?></p>
                </div>
                <div class="col-12">
                    <label class="form-label text-primary">Profile</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($user['profile'] ?? 'Not set'); ?></p>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="index.php?page=edit-profile&id=<?php echo htmlspecialchars($user['id']); ?>&from=list"
                    class="btn btn-primary btn-lg">Edit User <i class="fas fa-edit"></i></a>
                <a href="index.php?page=user-delete&id=<?php echo htmlspecialchars($user['id']); ?>&from=list"
                    class="btn btn-danger btn-lg ms-3"
                    onclick="return confirm('Are you sure you want to delete this user?');">Delete User <i
                        class="fas fa-trash"></i></a>
                <a href="index.php?page=list-profile" class="btn btn-secondary btn-lg ms-3">Back to List <i
                        class="fas fa-arrow-left"></i></a>
            </div>
        </div>
    </div>
</div>

<style>
.card-header {
    border-radius: 10px 10px 0 0;
}

.profile-img {
    border: 4px solid #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
}

.profile-img:hover {
    transform: scale(1.05);
}

.profile-img-placeholder {
    width: 150px;
    height: 150px;
    font-size: 24px;
    transition: transform 0.3s;
}

.profile-img-placeholder:hover {
    transform: scale(1.05);
}

.form-control-static {
    padding: 8px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
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

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    transition: background-color 0.3s;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #c82333;
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
</style>