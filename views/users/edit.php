<?php
require_once 'models/User.php';

$userModel = new User($pdo);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?page=dashboard&error=unauthorized');
    exit();
}

$userId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$user = $userId ? $userModel->findById($userId) : null;

if (!$user) {
    header('Location: index.php?page=list-profile&error=user_not_found');
    exit();
}

$updateSuccess = false;
$updateError = '';
$uploadSuccess = false;
$uploadError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
        $targetDir = __DIR__ . '/../../uploads/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetFile = $targetDir . basename($_FILES['profile_picture']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2 * 1024 * 1024;

        if (in_array($imageFileType, $allowedTypes) && $_FILES['profile_picture']['size'] <= $maxFileSize) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
                $filename = basename($_FILES['profile_picture']['name']);
                if ($userModel->updateProfilePicture($userId, $filename)) {
                    $uploadSuccess = true;
                    $user['profile_picture'] = $filename;
                } else {
                    $uploadError = 'Failed to update profile picture in the database.';
                    unlink($targetFile);
                }
            } else {
                $uploadError = 'Error uploading the profile picture.';
            }
        } else {
            $uploadError = 'Invalid file type or size exceeds 2MB. Allowed types: JPG, JPEG, PNG, GIF.';
        }
    }

    if (isset($_POST['username'])) {
        $data = [
            'username' => is_array($_POST['username']) ? implode(',', $_POST['username']) : $_POST['username'],
            'email' => is_array($_POST['email']) ? implode(',', $_POST['email']) : $_POST['email'],
            'phone' => is_array($_POST['phone']) ? implode(',', $_POST['phone']) : $_POST['phone'],
            'birthday' => is_array($_POST['birthday']) ? implode(',', $_POST['birthday']) : $_POST['birthday'],
            'profile' => is_array($_POST['profile']) ? implode(',', $_POST['profile']) : $_POST['profile'],
            'position' => is_array($_POST['position']) ? implode(',', $_POST['position']) : $_POST['position']
        ];

        if ($userModel->updateProfile($userId, $data)) {
            $updateSuccess = true;
            $user = $userModel->findById($userId);
            header('Location: index.php?page=list-profile');
            exit();
        } else {
            $updateError = 'Failed to update user profile. Please try again.';
        }
    }
}
?>

<div style="width: 100%;">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0">Edit User</h2>
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

            <form method="post" enctype="multipart/form-data" class="mb-4">
                <div class="mb-3 text-center">
                    <label for="profile_picture" class="form-label text-primary">Upload New Profile Picture</label>
                    <input type="file" class="form-control text-center" id="profile_picture" name="profile_picture"
                        accept=".jpg,.jpeg,.png,.gif" style="display: inline-block; width: auto;">
                    <button type="submit" class="btn btn-primary">Upload <i class="fas fa-upload"></i></button>
                </div>
            </form>

            <?php if ($uploadSuccess): ?>
            <div class="alert alert-success text-center animate__animated animate__fadeIn">Profile picture updated
                successfully for user ID <?php echo htmlspecialchars($userId); ?>!</div>
            <?php elseif ($uploadError): ?>
            <div class="alert alert-danger text-center animate__animated animate__shakeX">
                <?php echo htmlspecialchars($uploadError); ?></div>
            <?php endif; ?>

            <?php if ($updateSuccess): ?>
            <div class="alert alert-success text-center animate__animated animate__fadeIn mt-3">User profile updated
                successfully for user ID <?php echo htmlspecialchars($userId); ?>!</div>
            <?php elseif ($updateError): ?>
            <div class="alert alert-danger text-center animate__animated animate__shakeX mt-3">
                <?php echo htmlspecialchars($updateError); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label text-primary">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label text-primary">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label text-primary">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="position" class="form-label text-primary">Position</label>
                        <input type="text" class="form-control" id="position" name="position"
                            value="<?php echo htmlspecialchars($user['position'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="birthday" class="form-label text-primary">Birthday</label>
                        <input type="date" class="form-control" id="birthday" name="birthday"
                            value="<?php echo htmlspecialchars($user['birthday'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="role" class="form-label text-primary">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin
                            </option>
                            <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User
                            </option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="profile" class="form-label text-primary">Profile</label>
                        <textarea class="form-control" id="profile" name="profile"
                            rows="3"><?php echo htmlspecialchars($user['profile'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <div>
                        <a href="index.php?page=list-profile" class="btn btn-secondary btn-lg">Back to List <i
                                class="fas fa-arrow-left"></i></a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">Save Changes <i
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