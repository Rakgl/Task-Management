<?php
session_start();
require_once 'config/db_connect.php';
require_once './models/User.php';
ob_start();

$userModel = new User($pdo);
$username = $user['username'] ?? 'Unknown';
$email = $user['email'] ?? 'Unknown';
$role = $user['role'] ?? 'Unknown';

$page = isset($_GET['page']) ? htmlspecialchars($_GET['page'], ENT_QUOTES, 'UTF-8') : 'dashboard';

$public_pages = ['login', 'register', 'forgot-password'];
$standalone_pages = ['login', 'register', 'forgot-password'];

if (in_array($page, $standalone_pages, true)) {
    include __DIR__ . "/views/auth/{$page}.php";
    ob_end_flush();
    exit();
}

if (!isset($_SESSION['user_id']) && !in_array($page, $public_pages, true)) {
    header('Location: index.php?page=login');
    ob_end_flush();
    exit();
}

if (!isset($_SESSION['role']) && isset($_SESSION['user_id'])) {
    $userModel = new User($pdo);
    $_SESSION['role'] = $userModel->getRole($_SESSION['user_id']);
}

$allowed_pages = ['dashboard', 'logout', 'about','contact', 'profile', 'edit-profile'];
if ($_SESSION['user_id'] != 1 && !in_array($page, $allowed_pages, true)) {
    header('Location: index.php?page=dashboard');
    ob_end_flush(); 
    exit();
}

include_once './layout/header.php';
?>
<div class="container-dashboard">
    <?php
        include_once './layout/sidebar.php';

        switch ($page) {
            case 'about':
                include 'views/personal/about.php';
                break;
            case 'dashboard':
                include 'views/dashboard.php';
                break;
            case 'tasks':
                include_once 'views/tasks/index.php';
                break;
            case 'task-create':
                include_once 'views/tasks/create.php';
                break;
            case 'task-view':
                include_once 'views/tasks/view.php';
                break;
            case 'task-edit':
                include_once 'views/tasks/edit.php';
                break;
            case 'task-delete':
                include_once 'views/tasks/delete.php';
                break;
            case 'profile':
                include_once 'views/users/profile.php';
                break;
            case 'list-profile':
                include 'views/users/list-profile.php';
                break;
            case 'view-profile':
                include 'views/users/view-detail.php';
                break;
            case 'create-profile':
                include 'views/users/create.php';
                break;
            case 'edit-profile':
                include 'views/users/edit.php';
                break;
            case 'user-delete':
                include 'views/users/delete.php';
                break;
            case 'logout':
                include_once 'views/auth/logout.php';
                break;
            default:
                include_once 'views/dashboard.php';
                break;
        }
        ob_end_flush();
    ?>
    <?php include_once './layout/footer.php'; ?>
</div>

<style>
.container-dashboard {
    display: flex;
}
</style>