<?php
require_once 'models/User.php';

$userModel = new User($pdo);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?page=dashboard&error=unauthorized');
    exit();
}

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? 'user');
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $position = trim($_POST['position'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($userModel->findByEmail($email)) {
        $error = 'Email already exists.';
    } elseif (empty($username)) {
        $error = 'Username is required.';
    } else {
        if ($userModel->create($email, $password, $role, $username, $phone, $position)) {
            $success = true;
        } else {
            $error = 'Failed to create user. Please try again.';
        }
    }
}
?>

<div style="width: 100%;">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0">Create User</h2>
        </div>
        <div class="card-body">
            <?php if ($success): ?>
            <div class="alert alert-success text-center animate__animated animate__fadeIn">User created successfully!
            </div>
            <?php elseif ($error): ?>
            <div class="alert alert-danger text-center animate__animated animate__shakeX">
                <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label text-primary">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label text-primary">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label text-primary">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col-md-6">
                        <label for="role" class="form-label text-primary">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="user"
                                <?php echo (($_POST['role'] ?? 'user') === 'user') ? 'selected' : ''; ?>>User</option>
                            <option value="admin"
                                <?php echo (($_POST['role'] ?? 'user') === 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label text-primary">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="position" class="form-label text-primary">Position</label>
                        <input type="text" class="form-control" id="position" name="position"
                            value="<?php echo htmlspecialchars($_POST['position'] ?? ''); ?>">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Create User <i
                            class="fas fa-user-plus"></i></button>
                    <a href="index.php?page=profile" class="btn btn-secondary btn-lg">Back to Profile <i
                            class="fas fa-arrow-left"></i></a>

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