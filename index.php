<?php
session_start();
require_once 'config/db_connect.php';

// Sanitize the page parameter to prevent injection
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page'], ENT_QUOTES, 'UTF-8') : 'dashboard';

// Define public and standalone pages
$public_pages = ['login', 'register', 'forgot-password'];
$standalone_pages = ['login', 'register', 'forgot-password'];

// If the page is standalone, render it without layout and exit
if (in_array($page, $standalone_pages, true)) {
    include __DIR__ . "/views/auth/{$page}.php";
    exit();
}

// Authentication check: redirect to login if not authenticated and not on a public page
if (!isset($_SESSION['user_id']) && !in_array($page, $public_pages, true)) {
    header('Location: index.php?page=login');
    exit();
}

// Render the layout for non-standalone pages
include_once './layout/header.php';
?>
<div class="container-dashboard">
    <?php
        include_once './layout/sidebar.php';

        switch ($page) {
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
            case 'profile':
                include_once 'views/users/profile.php';
                break;
            case 'logout':
                include_once 'views/auth/logout.php';
                break;
        }
    ?>
    <?php include_once './layout/footer.php'; ?>
</div>

<style>
.container-dashboard {
    display: flex;
}
</style>