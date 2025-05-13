<?php
require_once 'models/User.php';


// Check admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?page=dashboard&error=unauthorized');
    exit();
}

// Initialize PDO (replace with your DB config)
$userModel = new User($pdo);

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Profile picture upload
    $filename = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $targetDir = __DIR__ . '/../../Uploads/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileInfo = pathinfo($_FILES['profile_picture']['name']);
        $imageFileType = strtolower($fileInfo['extension']);
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2 * 1024 * 1024;

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['profile_picture']['tmp_name']);
        finfo_close($finfo);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];

        // Generate unique filename
        $filename = uniqid('profile_') . '.' . $imageFileType;
        $targetFile = $targetDir . $filename;

        if (!in_array($imageFileType, $allowedTypes) || !in_array($mime, $allowedMimes)) {
            $error = 'Invalid file type. Allowed types: JPG, JPEG, PNG, GIF.';
        } elseif ($_FILES['profile_picture']['size'] > $maxFileSize) {
            $error = 'File size exceeds 2MB.';
        } elseif (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
            $error = 'Error uploading the profile picture.';
        }
    }

    // User creation
    if (!$error) {
        $username = filter_var(trim($_POST['username'] ?? ''), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password'] ?? '');
        $role = trim($_POST['role'] ?? 'user');
        $phone = filter_var(trim($_POST['phone'] ?? ''), FILTER_SANITIZE_STRING);
        $birthday = trim($_POST['birthday'] ?? '');
        $profile = filter_var(trim($_POST['profile'] ?? ''), FILTER_SANITIZE_STRING);
        $position = filter_var(trim($_POST['position'] ?? ''), FILTER_SANITIZE_STRING);

        $validRoles = ['user', 'admin'];
        // Validate birthday (not in the future)
        $today = new DateTime();
        $birthDate = $birthday ? DateTime::createFromFormat('Y-m-d', $birthday) : false;
        if ($birthday && (!$birthDate || $birthDate > $today)) {
            $error = 'Invalid birthday.';
        } elseif (empty($email) || empty($password)) {
            $error = 'Email and password are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif ($userModel->findByEmail($email)) {
            $error = 'Email already exists.';
        } elseif (empty($username)) {
            $error = 'Username is required.';
        } elseif (!in_array($role, $validRoles)) {
            $error = 'Invalid role selected.';
        } else {
            $pdo->beginTransaction();
            try {
                // Call create (assuming it returns true on success)
                if (!$userModel->create($email, $password, $role, $username, $phone, $position, $birthday, $profile)) {
                    throw new Exception('Failed to create user.');
                }
                $userId = $pdo->lastInsertId(); // Get the new user ID
                if ($filename && !$userModel->updateProfilePicture($userId, $filename)) {
                    throw new Exception('Failed to update profile picture.');
                }
                $pdo->commit();
                $success = true;
                header('Location: index.php?page=dashboard&success=user_created');
                exit();
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = 'Failed to create user: ' . $e->getMessage();
                if ($filename && file_exists($targetFile)) {
                    unlink($targetFile); // Clean up on failure
                }
            }
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
            <div class="alert alert-success text-center">User created successfully!</div>
            <?php elseif ($error): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="mb-4">
                <div class="mb-3 text-center">
                    <label for="profile_picture" class="form-label text-primary">Profile Picture</label>
                    <input type="file" class="form-control" id="profile_picture" name="profile_picture"
                        accept=".jpg,.jpeg,.png,.gif">
                </div>

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
                        <label for="phone" class="form-label text-primary">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="position" class="form-label text-primary">Position</label>
                        <input type="text" class="form-control" id="position" name="position"
                            value="<?php echo htmlspecialchars($_POST['position'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="birthday" class="form-label text-primary">Birthday</label>
                        <input type="date" class="form-control" id="birthday" name="birthday"
                            value="<?php echo htmlspecialchars($_POST['birthday'] ?? ''); ?>">
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
                    <div class="col-12">
                        <label for="profile" class="form-label text-primary">Profile</label>
                        <textarea class="form-control" id="profile" name="profile"
                            rows="3"><?php echo htmlspecialchars($_POST['profile'] ?? ''); ?></textarea>
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