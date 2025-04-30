<?php
    session_start();
    require_once 'config/db_connect.php';

    // Default route
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

    $standalone_pages = ['login', 'register', 'forgot-password'];

    if (in_array($page, $standalone_pages, true)) {
        include __DIR__ . "/views/auth/{$page}.php";
        exit();
    }
    
    // Authentication check
    $public_pages = ['login', 'register', 'forgot-password'];
    if (!isset($_SESSION['user_id']) && !in_array($page, $public_pages)) {
        header('Location: index.php?page=login');
        exit();
    }

    include_once './layout/header.php';
    include_once './layout/sidebar.php';
    switch ($page) {
        case 'dashboard':
            include_once 'views/dashboard.php';
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
        case 'login':
            include_once 'views/auth/login.php';
            break;
        case 'register':
            include_once 'views/auth/register.php';
            break;
        case 'logout':
            include_once 'controllers/logout.php';
            break;
        default:
            include_once 'views/404.php';
    }
    include_once './layout/footer.php'
?>