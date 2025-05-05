<?php
require_once 'models/User.php';

$userModel = new User($pdo);

$user = $userModel->findById($_SESSION['user_id']);

if (!$user) {
    header('Location: index.php?page=dashboard');
    exit();
}

$uploadSuccess = false;
$uploadError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
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
            if ($userModel->updateProfilePicture($_SESSION['user_id'], $filename)) {
                $uploadSuccess = true;
                $user['profile_picture'] = $filename;
            } else {
                $uploadError = 'Failed to update database with new profile picture.';
                unlink($targetFile);
            }
        } else {
            $uploadError = 'Error uploading file.';
        }
    } else {
        $uploadError = 'Invalid file type or size exceeds 2MB. Allowed types: JPG, JPEG, PNG, GIF.';
    }
}
?>

<div style="width: 100%;">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0">Profile</h2>
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
                <div>
                    <a href="index.php?page=edit-profile">edit profile</a>
                </div>
            </div>

            <form method="post" enctype="multipart/form-data" class="mb-4">
                <div class="mb-3 text-center">
                    <label for="profile_picture" class="form-label text-primary">Upload Profile Picture</label>
                    <input type="file" class="form-control form-control text-center" id="profile_picture"
                        name="profile_picture" accept=".jpg,.jpeg,.png,.gif"
                        style="display: inline-block; width: auto;">
                    <button type="submit" class="btn btn-primary">Upload <i class="fas fa-upload"></i></button>
                </div>
            </form>

            <?php if ($uploadSuccess): ?>
            <div class="alert alert-success text-center animate__animated animate__fadeIn">Profile picture uploaded
                successfully!</div>
            <?php elseif ($uploadError): ?>
            <div class="alert alert-danger text-center animate__animated animate__shakeX">
                <?php echo htmlspecialchars($uploadError); ?></div>
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <p class="mb-1"><strong class="text-primary">Username:</strong>
                                <?php echo htmlspecialchars($user['username'] ?? 'Not set'); ?></p>
                            <p class="mb-1"><strong class="text-primary">Email:</strong>
                                <?php echo htmlspecialchars($user['email'] ?? 'Not set'); ?></p>
                            <p class="mb-1"><strong class="text-primary">Role:</strong>
                                <?php echo htmlspecialchars(ucfirst($user['role'] ?? 'Not set')); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <p class="mb-1"><strong class="text-primary">Phone:</strong>
                                <?php echo htmlspecialchars($user['phone'] ?? 'Not set'); ?></p>
                            <p class="mb-1"><strong class="text-primary">Birthday:</strong>
                                <?php echo htmlspecialchars($user['birthday'] ?? 'Not set'); ?></p>
                            <p class="mb-1"><strong class="text-primary">Position:</strong>
                                <?php echo htmlspecialchars($user['position'] ?? 'Not set'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <p class="mb-1"><strong class="text-primary">Profile:</strong>
                                <?php echo htmlspecialchars($user['profile'] ?? 'Not set'); ?></p>
                            <p class="mb-1"><strong class="text-primary">Joined:</strong>
                                <?php echo htmlspecialchars($user['created_at'] ?? 'Not set'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="index.php?page=dashboard" class="btn btn-primary btn-lg">Back to Dashboard <i
                        class="fas fa-arrow-left"></i></a>
            </div>
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

.card {
    border-radius: 10px;
}

.card-body {
    padding: 20px;
}

.row g-3>.col-md-6 {
    padding: 10px;
}

.animate__animated {
    animation-duration: 1s;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.js"></script>