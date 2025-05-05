<?php
require_once 'models/User.php';

$userModel = new User($pdo);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?page=dashboard&error=unauthorized');
    exit();
}

$stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="width: 100%;">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0">List of Users</h2>
        </div>
        <div class="card-body">
            <?php if (empty($users)): ?>
            <div class="alert alert-info text-center">No users found.</div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username'] ?? 'Not set'); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                            <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                            <td>
                                <a href="index.php?page=view-profile&id=<?php echo htmlspecialchars($user['id']); ?>&from=list"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="index.php?page=edit-profile&id=<?php echo htmlspecialchars($user['id']); ?>&from=list"
                                    class="btn btn-primary btn-sm"
                                    onclick="return confirm('Are you sure you want to edit this user?');">
                                    <i class="fas fa-edit"></i>Edit
                                </a>
                                <a href="index.php?page=user-delete&id=<?php echo htmlspecialchars($user['id']); ?>&from=list"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this user?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <div class="mt-4">
                <a href="index.php?page=profile" class="btn btn-secondary btn-lg">Back to Profile <i
                        class="fas fa-arrow-left"></i></a>
            </div>
        </div>
    </div>
</div>

<style>
.card-header {
    border-radius: 10px 10px 0 0;
}

.table {
    margin-bottom: 0;
}

.table th,
.table td {
    vertical-align: middle;
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